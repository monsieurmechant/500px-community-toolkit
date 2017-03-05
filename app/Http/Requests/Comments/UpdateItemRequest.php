<?php

namespace App\Http\Requests\Comments;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @see \App\Policies\CommentPolicy
     * @return bool
     */
    public function authorize(): bool
    {
        $comment = \App\Comment::find($this->route('comment'));

        return $comment && $this->user()->can('update', $comment);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'read' => 'boolean',
        ];
    }
}
