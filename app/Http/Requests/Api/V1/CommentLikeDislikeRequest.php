<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\PostStatus;
use Illuminate\Foundation\Http\FormRequest;

class CommentLikeDislikeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'value' => ['nullable', 'in:0,1,null'],
        ];
    }
}
