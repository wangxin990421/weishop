<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendLoginPost extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'u_pwd'=>'required',
            'u_pwd'=>array("regex:/^[a-zA-Z0-9]{6,18}$/")
        ];
    }

    public function messages()
    {
        return [
            'u_pwd.required'=>'密码必填',
            'u_pwd.regex'=>'数字字母6-18位'
        ];
    }
}
