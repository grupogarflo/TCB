<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LanguageCreateRequest;
use App\language;

class LanguageController extends Controller
{
    //recupera todos los usuarios
    function getAllLanguage()
    {
        $res = language::select("id", "name", "abbrev")->where("active", 1)->get();
        return $res;
    }

    //agrega nuevos usuarios
    function addLanguage(LanguageCreateRequest $request)
    {
        $usuario = new language;
        $usuario->name = $request->name;
        $usuario->abbrev = $request->abbrev;
        $usuario->save();
        return response()->json([
            'message' => 'success'
        ], 200);
    }

    //update de el nombre y del email del usuario
    public function updateLanguage(LanguageCreateRequest $request)
    {
        $usuario = language::find($request->id);
        $usuario->name = $request->name;
        $usuario->abbrev = $request->abbrev;
        $usuario->save();

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    //elimina usuarios
    function deleteLanguage(Request $request)
    {
        try {
            //$task = language::findOrFail($request->id);
            //$task->delete();
            $cat = language::find($request->id);
            $cat->active = 0;
            $cat->save();
            return response()->json([
                'message' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'language not found or deleted'
            ], 404);
        }
    }
}
