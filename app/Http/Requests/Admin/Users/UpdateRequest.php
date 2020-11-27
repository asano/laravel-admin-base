<?php

namespace App\Http\Requests\Admin\Users;

/* 更新Requestクラス */
class UpdateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();

        // 更新時はnullを許可
        $rules['password'] =  ['nullable', 'max:100'];

        return $rules;
    }
}
