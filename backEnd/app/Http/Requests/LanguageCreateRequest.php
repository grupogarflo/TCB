<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class LanguageCreateRequest extends FormRequest
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
                'abbrev' => 'required|string | max:255'
            ];
        } else {
            return [
                'name'  => 'required|string|max:255',
                'abbrev' => 'required|string | max:255'
            ];
        }
    }

    public function messages()
    {
        return [
            'name.required' => 'El :attribute es obligatorio.',
            'abbrev.required' => 'El :attribute es obligatorio'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nombre del idioma',
            'abbrev' => 'Abreviatura'
        ];
    }
}
