<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Users\ImportRequest;
use Illuminate\Support\MessageBag;

use App\Services\UserService;

class ImportController extends Controller
{
    /**
     * @var int ヘッダーの数
     */
    const HEADER_COUNT = 2;

    /**
     * @var UserService
     */
    protected $service;

    /**
     * @var array
     */
    protected $configs = [];

    /**
     * Create a new controller instance.
     *
     * @param  UserService  $service
     * @return void
     */
    public function __construct(UserService $service)
    {
        // サービスの保持
        $this->service = $service;

        // コントローラ内共通設定
        $this->configs['common'] = \Config::get('apps.admin.common');
        $this->configs['users'] = \Config::get('apps.admin.users');
        $this->configs['import'] = \Config::get('apps.admin.users.import');
    }

    /**
     * Enable the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // configsは基本的にはconfigs下に置いたファイルをそのまま入れる
        $configs = array_merge(
            $this->configs,
            [
                /* 追加があれば */
            ]
        );

        $indata = [
            /* 初期値で何かあれば */
        ];

        // セッションを念の為削除
        $request->session()->forget('fileData');

        return view(
            'admin.users.import.create',
            compact(
                'indata',
                'configs'
            )
        );
    }

    /**
     * 一括インポート確認
     *
     * @param  App\Http\Requests\Admin\Users\ImportRequest $request
     * @return \Illuminate\Http\Response
     */
    public function confirm(ImportRequest $request)
    {
        $request->session()->forget('fileData');
        $header = array_column($this->configs['import']['header'], 'label');

        if ($request->file('file')->isValid()) {
            //ファイル名取得
            $path = $request->file('file')->path();
            $data = file_get_contents($path);
            if (mb_detect_encoding($data) != 'UTF-8') {
                $data = mb_convert_encoding($data, 'UTF-8', "ASCII,JIS,UTF-8,EUC-JP,SJIS,SJIS-win");
            }
            $temp = tmpfile();
            $meta = stream_get_meta_data($temp);

            fwrite($temp, $data);
            rewind($temp);

            $file = new \SplFileObject($meta['uri'], 'rb');
            $file->setFlags(
                \SplFileObject::READ_CSV   |    // CSV列として行読み込み
                \SplFileObject::READ_AHEAD |    // 先読み/巻き戻し
                \SplFileObject::SKIP_EMPTY |    // 空行読み飛ばし
                \SplFileObject::DROP_NEW_LINE   // 行末の改行読み飛ばし
            );

            $messages = new MessageBag();
            // ファイルが空の場合
            if (empty($file)) {
                $messages->add('file', 'ファイルの中身が空です。');
                return redirect()->route('admin.users.import.create')->withErrors($messages);
            }

            $fileData = [];
            $emails = [];
            $count = 1;
            // 一行ずつ処理
            foreach ($file as $key => $line) {

                // 空である場合はとばす
                if (empty(array_filter($line, 'strlen'))) {
                    $count++;
                    continue;
                }

                $incorrect = false;
                // ヘッダー行の場合はとばす
                if (count($line) == self::HEADER_COUNT) {
                    if ($line == $header) {
                        continue;
                    } elseif ($key == 0) {
                        $incorrect = true;
                    }
                } else {
                    $incorrect = true;
                }

                if ($incorrect) {
                    // ヘッダーの数が合わない場合はエラー
                    $messages->add('file', '所定の形式のファイルをアップロードしてください。');
                    return redirect()->route('admin.users.import.create')->withErrors($messages);
                }

                // 配列に格納
                $fileData[$count] = [
                    'name'  => $line[0]  ?? '',
                    'email' => $line[1]  ?? '',
                ];

                // バリデーション実行
                $importRequest = new ImportRequest();
                $validator = \Validator::make(
                    $fileData[$count],
                    $importRequest->rules($line),
                    $importRequest->messages($line),
                    $importRequest->attributes($line)
                );
                $validator->addDependentExtension('duplicate', function ($attribute, $value, $parameters, $validator) use ($emails) {
                    return !in_array($value, $emails);
                });
                if ($validator->fails()) {
                    $errorList[$count] = $validator->errors()->all();
                }
                $emails[] = $line[1];
                $count++;
            }
            fclose($temp);
        }
        if (!empty($errorList)) {
            return redirect()->route('admin.users.import.create')->with('errorList', $errorList);
        }
        $request->session()->put('fileData', $fileData);
        return view('admin.users.import.confirm', [
            'fileData' => $fileData
        ]);
    }

    /**
     * 一括インポート保存処理
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        $date = date('Y-m-d H:i:s');

        $input = $request->session()->get('fileData');
        $request->session()->forget('fileData');
        $messages = new MessageBag();
        // 空の場合
        if (empty($input)) {
            $messages->add('file', 'ファイルのデータが不正です。');
            return redirect()->route('admin.users.import.create')->withErrors($messages);
        }

        // パスワード生成
        foreach ($input as $key => $data) {
            $input[$key]['password'] = \Str::random(12);
        }

        // 保存処理
        $return = $this->service->importSave($input, $date);
        if (!is_bool($return)) {
            throw $return;
        }
        // セッションで保持
        $request->session()->flash('fileData', $input);
        return view('admin.users.import.complete');
    }

    /**
     * CSV ダウンロード
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function csvDownload(Request $request)
    {
        $result = $request->session()->pull('fileData');
        $request->session()->forget('fileData');

        if (empty($result)) {
            abort(404);
        }

        $columns = [
            '名前',
            'メールアドレス',
            'パスワード'
        ];

        $stream = fopen('php://temp', 'r+b');
        fputcsv($stream, $columns);

        foreach ($result as $key => $row) {
            // 存在しないファイル名またはメールアドレスが来た場合はエラーにする
            $validator = \Validator::make($row, [
                'name'  => 'exists:users,name',
                'email' => 'exists:users,email'
            ]);
            if ($validator->fails()) {
                abort(404);
            }
            fputcsv($stream, array($row['name'], $row['email'], $row['password']));
        }

        rewind($stream);

        $csv = str_replace(PHP_EOL, "\r\n", stream_get_contents($stream));
        $csv = mb_convert_encoding($csv, 'SJIS-win', 'UTF-8');

        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="users.csv"',
        );

        return \Response::make($csv, 200, $headers);
    }

    /**
     * サンプル ダウンロード
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sampleDownload(Request $request)
    {
        $config = \Config::get('apps.admin.users.sample_export');
        array_unshift($config['column'], $config['header']);

        $stream = fopen('php://temp', 'r+b');
        foreach ($config['column'] as $row) {
            fputcsv($stream, $row);
        }
        rewind($stream);

        $csv = str_replace(PHP_EOL, "\r\n", stream_get_contents($stream));
        $csv = mb_convert_encoding($csv, 'SJIS-win', 'UTF-8');

        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_users.csv"',
        );

        return \Response::make($csv, 200, $headers);
    }
}
