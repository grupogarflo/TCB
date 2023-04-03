<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;


class UserCreateRequest extends FormRequest
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
        /*
        if (User::where('email', $request->email)->count() > 0) {
            return response()->json([
                'result' => 'email_duplicate'
            ], 422);
        }*/
        if ($request->id == 0) {

            return [
                'name'  => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|max:255'
            ];
        } else {

            return [
                'name'  => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'id' => 'required| integer'
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
