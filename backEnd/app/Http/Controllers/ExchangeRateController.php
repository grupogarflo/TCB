<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ExchangeRateCreateRequest;
use App\exchangeRate;

class ExchangeRateController extends Controller
{
    //recupera todos los usuarios
    function getAllRate()
    {
        $res = exchangeRate::select("id", "rate", "start_date", "end_date")->get();
        return $res;
    }

    //agrega nuevos usuarios
    function addURate(ExchangeRateCreateRequest $request)
    {
        $usuario = new exchangeRate;
        $usuario->rate = $request->rate;
        $usuario->start_date = $request->start_date;
        $usuario->end_date = ($request->end_date == "") ? NULL : $request->end_date;
        $usuario->save();
        return response()->json([
            'message' => 'success'
        ], 200);
    }

    //update de el nombre y del email del usuario
    public function upateRAte(ExchangeRateCreateRequest $request)
    {
        $usuario = exchangeRate::find($request->id);
        $usuario->rate = $request->rate;
        $usuario->start_date = $request->start_date;
        $usuario->end_date = ($request->end_date == "") ? NULL : $request->end_date;
        $usuario->save();

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    //elimina usuarios
    function deleteRate(Request $request)
    {
        try {
            $task = exchangeRate::findOrFail($request->id);
            $task->delete();
            return response()->json([
                'message' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'rate not found or deleted'
            ], 404);
        }
    }
}
