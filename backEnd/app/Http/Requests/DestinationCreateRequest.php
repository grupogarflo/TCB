<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class DestinationCreateRequest extends FormRequest
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
    public function rules(Request $request)
    {
        if ($request->id == 0) {
            return [
                'name'  => 'required|string|max:255',
                'title' => 'required|string | max:255',
                'url' => 'required|string | max:255',
                'show_home' => 'required|boolean | max:1',
                //'img' => 'required|string | max:255',
                'description' => 'required|string',
                'description_footer' => 'required|string',
                'meta_title' => 'required|string',
                'meta_description' => 'required|string',
                'meta_keywords' => 'required|string'

            ];
        } else {
            return [
                'name'  => 'required|string|max:255',
                'title' => 'required|string | max:255',
                'url' => 'required|string | max:255',
                'show_home' => 'required|boolean | max:1',
                //'img' => 'required|string | max:255',
                'description' => 'required|string',
                'description_footer' => 'required|string',
                'meta_title' => 'required|string',
                'meta_description' => 'required|string',
                'meta_keywords' => 'required|string'

            ];
        }
    }

    public function messages()
    {
        return [
            'name.required' => 'El :attribute es obligatorio.',
            'title.required' => 'El :attribute es obligatorio',
            'url.required' => 'La :attribute es obligatorio',
            'img' => 'La :attribute es obligatorio',
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
            'name' => 'Nombre del idioma',
            'title' => 'Titulo',
            'url' => 'Url',
            'show_home' => 'Se muestra en el home',
            'img' => 'imagen',
            'description' => 'Descripcion',
            'description_footer' => 'Descripcion del footer',
            'meta_title' => 'Meta titulo',
            'meta_description' => 'Meta descripcion',
            'meta_keywords' => 'Meta keywords'
        ];
    }
}
