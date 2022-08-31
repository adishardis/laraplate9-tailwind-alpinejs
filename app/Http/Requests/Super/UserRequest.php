<?php

namespace App\Http\Requests\Super;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class UserRequest extends FormRequest
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
                    'username' => ['string', 'max:255'],
                    'name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                    'password' => ['required', Rules\Password::defaults()],
                    'role_name' => ['required', 'array', 'min:1'],
                ]
            ),
            'PUT' => (
                [
                    'username' => ['nullable', 'string', 'max:255'],
                    'name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$this->user_id],
                    'password' => ['nullable', Rules\Password::defaults()],
                    'role_name' => ['required', 'array', 'min:1'],
                ]
            )
        };
    }
}
