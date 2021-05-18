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
                    'status' => 'required|boolean'
                ];
                break;

            case 'PATCH':
                return [
                    'title' => 'string',
                    'name' => 'string',
                    'status' => 'boolean'
                ];
                break;
        }
    }
}
