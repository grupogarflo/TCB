<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Validator;

class PaymentCreateRequest extends FormRequest
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
        if ($request->clientId == 0) {
            return [
                'name'  => 'required|string',
                'email' => 'required|string',
                'phone' => 'required|string',
                'code_book' => 'required|numeric',
                'site_book' => 'required|string',
                'language' => 'required|string',
                'status' => 'required|string',
                'toursInfo' => 'required',
                //'tours_id' => 'required|integer',
                //'date' => 'required|date',
                //'adult' => 'required|integer',
                //'child' => 'required|integer',
                //'promocode' => 'required|string',
                //'discount' => 'required|float',
                //'total' => 'required|flioat',
                'currency' => 'required|string',
                'amount' => 'required|numeric',
                // 'hotel' => 'required',
            ];
        } else {
            return [
                'clientId'  => 'required|numeric',
                'status'  => 'required|string',
                'authorization' => 'required|string'
            ];
        }
    }
}
