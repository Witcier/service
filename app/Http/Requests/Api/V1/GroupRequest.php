<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Validation\Rule;

class GroupRequest extends FormRequest
{
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'title' => 'required|string',
                    'parent_id' => 'exists:groups,id',
                    'remark' => 'string',
                    'type' => 'required|integer',
                    'status' => 'required|boolean',
                    'rules' => [
                        'required',
                        'array',
                        Rule::exists('rules', 'id')->whereIn('id', $this->rules),
                    ],
                ];
                break;

            case 'PATCH':
                return [
                    'title' => 'string',
                    'parent_id' => 'exists:groups,id',
                    'remark' => 'string',
                    'type' => 'integer',
                    'status' => 'boolean',
                    'rules' => [
                        'array',
                        Rule::exists('rules', 'id')->whereIn('id', $this->rules),
                    ],
                ];
                break;
        }
    }
}
