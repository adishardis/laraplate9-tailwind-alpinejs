<?php

namespace App\Http\Requests\Super;

use App\Enums\PostStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class PostRequest extends FormRequest
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
                    'title' => ['required', 'string', 'max:255'],
                    'description' => ['required', 'string', 'max:255'],
                    'status' => ['required', 'in:'.(implode(',', PostStatus::values()))],
                ]
            ),
            'PUT' => (
                [
                    'title' => ['required', 'string', 'max:255'],
                    'description' => ['required', 'string', 'max:255'],
                    'status' => ['required', 'in:'.(implode(',', PostStatus::values()))],
                ]
            )
        };
    }
}
