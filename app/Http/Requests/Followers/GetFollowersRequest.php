<?php

namespace App\Http\Requests\Followers;

use Illuminate\Foundation\Http\FormRequest;

class GetFollowersRequest extends FormRequest
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
            'order-by' => 'string|in:followers,affection',
            'limit'    => 'numeric|max:50',
            'skip'     => 'numeric'
        ];
    }
}
