<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Users\StoreRequest;
use App\Http\Requests\Admin\Users\UpdateRequest;

use App\Services\UserService;

class UsersController extends Controller
{
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
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->input();

        // 検索
        $items = $this->service->getUsers(
            $input,
            [
                'users.id',
                'users.name',
                'users.email',
                'users.is_enabled',
                'users.last_login_at',
                'users.created_at',
                'users.updated_at',
            ]
        );

        // configsは基本的にはconfigs下に置いたファイルをそのまま入れる
        $configs = array_merge(
            $this->configs,
            [
                /* 追加があれば */
            ]
        );

        return view(
            'admin.users.index',
            compact(
                'input',
                'items',
                'configs'
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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

        return view(
            'admin.users.create',
            compact(
                'indata',
                'configs'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $input = $request->filterInput();

        // execute
        $return = $this->service->store($input);

        if (!is_numeric($return)) {
            throw $return;
        }

        return redirect()
            //->route('admin.users.show', ['id' => $return])
            ->route('admin.users.index')
            ->with('flash_message', '登録しました。');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // configsは基本的にはconfigs下に置いたファイルをそのまま入れる
        $configs = array_merge(
            $this->configs,
            [
                /* 追加があれば */
            ]
        );

        $indata = $this->service->getUser($id, [
            'id',
            'name',
            'email',
            'is_enabled',
            'last_login_at',
            'created_at',
            'updated_at',
        ]);

        return view(
            'admin.users.show',
            compact(
                'indata',
                'configs'
            )
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // configsは基本的にはconfigs下に置いたファイルをそのまま入れる
        $configs = array_merge(
            $this->configs,
            [
                /* 追加があれば */
            ]
        );

        $indata = $this->service->getUser($id, [
            'id',
            'name',
            'email',
            'is_enabled',
        ]);

        return view(
            'admin.users.edit',
            compact(
                'indata',
                'configs'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $input = $request->filterInput();

        // execute
        $return = $this->service->update($id, $input);

        if (!is_numeric($return)) {
            throw $return;
        }

        return redirect()
            //->route('admin.users.show', ['id' => $return])
            ->route('admin.users.index')
            ->with('flash_message', '登録しました。');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Enable the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function enable(Request $request)
    {
        $ids = $request->input('ids');

        abort_if(!$ids, 403);

        $return = $this->service->batchEnable($ids);

        if (!is_numeric($return)) {
            // そうじゃないものに引っかかった
            throw $return;
        }

        return redirect()
            //->route('admin.users.show', ['id' => $return])
            ->route('admin.users.index')
            ->with('flash_message', '有効に変更しました。');
    }

    /**
     * Disable the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function disable(Request $request)
    {
        $ids = $request->input('ids');

        abort_if(!$ids, 403);

        $return = $this->service->batchDisable($ids);

        if (!is_numeric($return)) {
            // そうじゃないものに引っかかった
            throw $return;
        }

        return redirect()
            //->route('admin.users.show', ['id' => $return])
            ->route('admin.users.index')
            ->with('flash_message', '無効に変更しました。');
    }
}
