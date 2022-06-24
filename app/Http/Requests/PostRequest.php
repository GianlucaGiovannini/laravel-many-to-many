<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
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
            'title' => ['required', 'title' => 
            [
                'required', Rule::unique('posts', 'title')->ignore($this->post)
            ]
        ],
            'category_id' => ['nullable','exists:categories,id'],
            'tags' => ['exists:tags,id'],  // bug da risolvere -_-
            'cover_image' => ['nullable'],
            'content' => ['nullable']
        ];
    }
}
