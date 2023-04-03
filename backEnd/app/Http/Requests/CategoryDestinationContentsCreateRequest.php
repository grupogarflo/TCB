<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class CategoryDestinationContentsCreateRequest extends FormRequest
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

            'url' => 'required|string | max:255',
            'description' => 'required|string',
            'description_footer' => 'required|string',
            'meta_title' => 'required|string',
            'meta_description' => 'required|string',
            'meta_keywords' => 'required|string'

        ];
    }
    public function messages()
    {
        return [

            'url.required' => 'La :attribute es obligatorio',
            'description.required' => 'La :attribute es obligatorio',
            'description_footer.required' => 'La :attribute es obligatorio',
            'meta_title.required' => 'El :attribute es obligatorio',
            'meta_description.required' => 'El :attribute es obligatorio',
            'meta_keywords.required' => 'El :attribute es obligatorio'
        ];
    }

    public function attributes()
    {
        return [

            'url' => 'Url',
            'description' => 'Descripcion',
            'description_footer' => 'Descripcion del footer',
            'meta_title' => 'Meta titulo',
            'meta_description' => 'Meta descripcion',
            'meta_keywords' => 'Meta keywords'
        ];
    }
}
