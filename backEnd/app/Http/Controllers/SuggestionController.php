<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Suggestion;
use App\Http\Requests\SuggestionIconFormRequest;

class SuggestionController extends Controller
{
    function getAllIcon()
    {
        $res = Suggestion::select(
            "*"
        )
            ->where('active', 1)
            ->orderBy('name_esp', 'ASC')
            ->get();
        return $res;
    }

    function addIcon(SuggestionIconFormRequest $request)
    {

        $Suggestion = new Suggestion();
        $Suggestion->icon = $request->icon;
        $Suggestion->name_ing = $request->nameIng;
        $Suggestion->name_esp = $request->nameEsp;
        $Suggestion->save();
        return response()->json([
            'message' => 'success'
        ], 200);
    }

    function updateIcon(SuggestionIconFormRequest $request)
    {

        Suggestion::where('id', $request->id)
            ->update([
                "icon" => $request->icon,
                "name_esp" => $request->nameEsp,
                "name_ing" => $request->nameIng
            ]);

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    function deleteIcon(Request $request)
    {
        try {
            $cat = Suggestion::find($request->id);
            $cat->active = 0;
            $cat->save();

            return response()->json([
                'message' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'icon not found or deleted'
            ], 404);
        }
    }

    function getIconInfo(Request $request)
    {

        $res = Suggestion::select(
            "*"
        )
            ->where('id', $request->id)
            ->get();

        if (count($res) > 0) {
            return $res;
        } else {
            return response()->json([
                'message' => 'icon not found or deleted'
            ], 404);
        }
    }
}
