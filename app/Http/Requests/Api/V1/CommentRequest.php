<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

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
            'GET' => (
                [
                    'post_id' => ['required', 'numeric'],
                ]
            ),
            'POST' => (
                [
                    'post_id' => ['required', 'numeric'],
                    'parent_id' => ['nullable', 'numeric'],
                    'comment' => ['string', 'max:255'],
                ]
            )
        };
    }
}
