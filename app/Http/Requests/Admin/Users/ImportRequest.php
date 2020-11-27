<?php

namespace App\Http\Requests\Admin\Users;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class ImportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData()
    {
        return $this->all();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules($line = null)
    {
        if (!empty($line)) {
            return [
                'name'       => [
                    'required',
                    'max:100',
                ],
                'email'      => [
                    'required',
                    'max:100',
                    'unique:users,email',
                    'duplicate',
                ],
            ];
        } else {
            return [
                'file' => [
                    'required',
                    'file',
                    'mimes:csv,txt'
                ]
            ];
        }
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages($line = null)
    {
        if (!empty($line)) {
            return [
                'email.unique'    => 'メールアドレスの値は既に存在しています。（'.$line[1].'）',
                'email.duplicate' => '同一リスト内でメールアドレスが重複しています。（'.$line[1].'）',
            ];
        } else {
            return [];
        }
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes($line = null)
    {
        if (!empty($line)) {
            // メッセージの要素名部分を変換する
            return [
                'name'       => '名前',
                'email'      => 'メールアドレス',
            ];
        } else {
            return [
                'file' => 'ファイル'
            ];
        }
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = [];
        $validatorErrors = $validator->errors()->toArray();

        // check.0のような形で来ることがあるので、形を変更している
        foreach ($validatorErrors as $key => $err) {
            if (0 === strpos($key, 'check')) {
                if (!isset($error['check'])) {
                    // xxxのエラーがなければxxx.nのエラー文字列を入れる
                    $errors['check'] = $err;
                }
            } else {
                $errors[$key] = $err;
            }
        }

        // メッセージの調整
        $error = ValidationException::withMessages($errors);
        throw $error;
    }

    /**
     * 送信されたデータの必要な部分だけを返す
     * Controller側で$request->all()の代わりに呼び出す
     *
     * @return array
     */
    public function filterInput()
    {
        $attr = array_keys($this->attributes());

        // 配列で渡されるデータは複雑な形をしているので、不要な部分を消す。
        // unset($attr['check.*']);
        // $attr[] = 'check';

        // 入力チェックを通過したデータのみにする
        return $this->only($attr);
    }
}
