<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class TourCreateRequest extends FormRequest
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
                'sub_title' => 'required|string | max:255',
                'url' => 'required|string | max:255',
                // 'rank' => 'required|integer | max:255',
                // 'top_home' => 'required|boolean | max:1',
                // 'description_small' => 'required|string',
                'description' => 'required|string',
                'not_include' => 'required|string',
                'avaible' => 'required|string | max:255',
                'duration' => 'required|string | max:255',
                'includes' => 'required|string',
                'bring' => 'required|string',
                'note' => 'required|string',
                'meta_title' => 'required|string',
                'meta_description' => 'required|string',
                'meta_keywords' => 'required|string'


            ];
        } else {
            return [
                'name'  => 'required|string|max:255',
                'sub_title' => 'required|string | max:255',
                'url' => 'required|string | max:255',
                // 'rank' => 'required|integer | max:11',
                // 'top_home' => 'required|boolean | max:1',
                // 'description_small' => 'required|string',
                'description' => 'required|string',
                'not_include' => 'required|string',
                'avaible' => 'required|string | max:255',
                'duration' => 'required|string | max:255',
                'includes' => 'required|string',
                'bring' => 'required|string',
                'note' => 'required|string',
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
            'sub_title.required' => 'El :attribute es obligatorio',
            'url.required' => 'La :attribute es obligatorio',
            // 'rank.required' => 'La :attribute es obligatorio',
            //'description_small.required' => 'La :attribute es obligatorio',
            'description.required' => 'La :attribute es obligatorio',
            'not_include.required' => 'La :attribute es obligatorio',
            'avaible.required' => 'El :attribute es obligatorio',
            'duration.required' => 'El :attribute es obligatorio',
            'includes.required' => 'La :attribute es obligatorio',
            'bring.required' => 'La :attribute es obligatorio',
            'note.required' => 'La :attribute es obligatorio',
            'meta_title.required' => 'El :attribute es obligatorio',
            'meta_description.required' => 'El :attribute es obligatorio',
            'meta_keywords.required' => 'El :attribute es obligatorio',
            'peek_id.required' => 'La :attribute es obligatorio',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nombre del idioma',
            'sub_title.required' => 'Subtitulo',
            'url.required' => 'Url',
            // 'rank.required' => 'Rank',
            // 'description_small.required' => 'Descripción breve',
            'description_show.required' => 'Descripción mostrar',
            'description_hide.required' => 'Descripción oculta',
            'avaible.required' => 'Disponibilidad',
            'duration.required' => 'Duracion',
            'includes.required' => 'Incluye',
            'bring.required' => 'Que traer',
            'note.required' => 'Notas',
            'meta_title.required' => 'Meta title',
            'meta_description.required' => 'Meta description',
            'meta_keywords.required' => 'Meya keywords',
            'peek_id.required' => 'Id de peek',
        ];
    }
}
