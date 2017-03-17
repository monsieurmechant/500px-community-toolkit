<?php

namespace App\Http\Requests\Photos;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $photo = \App\Photo::find($this->route('photo'));

        return $photo && $this->user()->can('update', $photo);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'read_comments' => 'boolean',
            'includes'      => 'string|in:user,comments',
        ];
    }
}
