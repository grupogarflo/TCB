<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class ExchangeRateCreateRequest extends FormRequest
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
                'rate'  => 'required||between:0,99.99',
                'start_date' => 'required|date_format:Y-m-d'
            ];
        } else {

            return [
                'rate'  => 'required||between:0,99.99',
                'start_date' => 'required|date_format:Y-m-d'
            ];
        }
    }
    public function messages()
    {
        return [
            'name.required' => 'El :attribute es obligatorio.',
            'email.required' => 'El :attribute es obligatorio',
            'password.min' => 'El :attribute es obligatorio'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nombre del usuario',
            'email' => 'email',
            'password' => 'password'
        ];
    }
}
