<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class SuggestionIconFormRequest extends FormRequest
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
            'nameIng'  => 'required|string|max:255',
            'nameEsp'  => 'required|string|max:255',
            'icon'  => 'required|string|max:255',
        ];
    }
    public function messages()
    {
        return [
            'nameIng.required' => 'El :attribute es obligatorio.',
            'nameEsp.required' => 'El :attribute es obligatorio',
            'icon.required' => 'La :attribute es obligatorio',
        ];
    }
    public function attributes()
    {
        return [
            'nameIng' => 'Nombre en ingles es obligatorio',
            'nameEsp.required' => 'Nombre en español es obligatorio',
            'icon.required' => 'Icon es obligatorio'
        ];
    }
}
