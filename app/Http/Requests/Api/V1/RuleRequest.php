<?php

namespace App\Http\Requests\Api\V1;

class RuleRequest extends FormRequest
{
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'title' => 'required|string',
                    'name' => 'required|string',
                    'parent_id' => 'exists:rules,id',
                    'status' => 'required|boolean'
                ];
                break;

            case 'PATCH':
                return [
                    'title' => 'string',
                    'name' => 'string',
                    'parent_id' => 'exists:rules,id',
                    'status' => 'boolean'
                ];
                break;
        }
    }
}
