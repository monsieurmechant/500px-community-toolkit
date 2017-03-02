<?php

namespace App\Http\Requests\Photos;

use Illuminate\Foundation\Http\FormRequest;

class GetCollectionRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'includes'        => 'string|in:user,comments',
            'unread_comments' => 'boolean',
            'cursor'          => 'string',
            'previous'        => 'string'
        ];
    }
}
