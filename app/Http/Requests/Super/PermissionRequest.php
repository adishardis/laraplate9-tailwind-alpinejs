<?php

namespace App\Http\Requests\Super;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class PermissionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return match ($this->method()) {
            'POST' => (
                [
                    'name' => ['required', 'string', 'max:255', 'unique:permissions'],
                    'display_name' => ['required', 'string', 'max:255'],
                    'role_name' => ['required', 'array', 'min:1'],
                ]
            ),
            'PUT' => (
                [
                    'name' => ['required', 'string', 'max:255', 'unique:permissions,name,'.$this->permission_id],
                    'display_name' => ['required', 'string', 'max:255'],
                    'role_name' => ['required', 'array', 'min:1'],
                ]
            )
        };
    }
}
