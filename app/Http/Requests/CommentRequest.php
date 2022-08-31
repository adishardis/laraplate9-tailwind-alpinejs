<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
                    'post_id' => ['required', 'numeric'],
                    'parent_id' => ['nullable', 'numeric'],
                    'comment' => ['required', 'string', 'max:200'],
                ]
            ),
            'PUT' => (
                [
                    'post_id' => ['required', 'numeric'],
                    'parent_id' => ['nullable', 'numeric'],
                    'comment' => ['required', 'string', 'max:200'],
                ]
            )
        };
    }
}
