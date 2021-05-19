<?php

namespace App\Http\Requests\Api\V1;

use Auth;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function rules()
    {
        return [
            // 'username' => 'required|unique:users,username,' . Auth::id(),
            'password' => 'required|min:8', 
            'phone' => 'regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199)\d{8}$/',
            'realname' => 'required|string',
            'remark' => 'string',
            'platform_id' => 'required|exists:platforms,id',
            'groups' => [
                'required',
                'array',
                Rule::exists('groups', 'id')->whereIn('id', $this->groups),
            ],
        ];
    }
}
