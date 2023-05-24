<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CategoryDestinationContentsCreateRequest;

use App\categoryDestinationContent;
//use App\categoryDestination;
use App\category;
use App\destination;
use App\language;


use Illuminate\Support\Facades\DB;

class CategoryDestinationContents extends Controller
{
    //recuepra todos los registros
    function getAllCategoriDestination()
    {
        $res = categoryDestinationContent::select("*")
            ->join('link_table', '.category_destination_content_id', '=', 'category_destination_contents.id')
            ->where('category_destination_contents.active', 1)
            ->orderBy('url', 'ASC')
            ->get();
        /*
        //anterior
        $res = categoryDestinationContent::select("category_destination_contents.*", ".*")
            ->join('', '.category_destination_content_id', '=', 'category_destination_contents.id')
            ->where('category_destination_contents.active', 1)
            ->orderBy('url', 'ASC')
            ->get();
        */

        /*
        $res = categoryDestinationContent::select(
            "*",
        )->where('active', 1)
            ->orderBy('url', 'ASC')
            ->get();
        */
        //->toSql();
        //$res = categoryDestinationContent::with('espIdiomaSolo')->where('active', 1)->get();

        return $res;
    }

    //agrega nuevos registros
    function addCategoriDestination(CategoryDestinationContentsCreateRequest $request)
    {
        $catContenido = new categoryDestinationContent;
        $catContenido->url = $request->url;
        $catContenido->description = $request->description;
        $catContenido->description_footer = $request->description_footer;
        $catContenido->meta_title = $request->meta_title;
        $catContenido->meta_description = $request->meta_description;
        $catContenido->meta_keywords = $request->meta_keywords;
        $catContenido->save();
        //$ultimoID = $catContenido->id;

        $catContenido->category()->attach(
            $request->categoria,
            [

                'destination_id' => $request->destino,
                'language_id' =>  $request->idioma
            ]
        );

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    //recupera informacion de las categorias
    public function getCategori()
    {
        $res = category::select(
            "categories.id",
            "category_contents.name"
        )
            ->join('category_contents', 'category_contents.category_id', '=', 'categories.id')
            ->where('categories.active', 1)
            ->where('category_contents.language_id', 1)
            ->orderBy('category_contents.name', 'ASC')
            ->get()->toArray();
        //->toSql();
        if (count($res) > 0) {
            return response()->json($res);
        } else {
            return response()->json([
                'message' => 'categories not found or deleted'
            ], 404);
        }
    }

    //recupera informacion de los destinos
    public function getDestination()
    {
        $res = destination::select(
            "destinations.id",
            "destination_contents.name"
        )
            ->join(
                'destination_contents',
                'destination_contents.destination_id',
                '=',
                'destinations.id'
            )
            ->where('destinations.active', 1)
            ->where('destination_contents.language_id', 1)
            ->orderBy('destination_contents.name', 'ASC')
            ->get()->toArray();
        //->toSql();
        if (count($res) > 0) {
            return $res;
        } else {
            return response()->json([
                'message' => 'destination not found or deleted'
            ], 404);
        }
    }

    //recupera la informacion de la categoria por idioma
    function getCategoriDestinationInfo(Request $request)
    {
        /*
        $res = categoryDestinationContent::select("category_destination_contents.*", ".*")
            ->join('', '.category_destination_content_id', '=', 'category_destination_contents.id')
            ->where('.category_destination_contents.id', $request->id)
            ->where('.language_id', $request->idioma)
            ->get();
        */
        $res = categoryDestinationContent::select("*")
            ->join('link_table', '.category_destination_content_id', '=', 'category_destination_contents.id')
            ->where('category_destination_contents.id', $request->id)
            ->where('link_table.language_id', $request->idioma)
            ->get();


        if (count($res) > 0) {
            return $res;
        } else {
            return response()->json([
                'message' => 'categories not found or deleted'
            ], 404);
        }
    }

    //elimina categorias
    function deleteCategoriDestination(Request $request)
    {
        try {
            $cat = categoryDestinationContent::find($request->id);
            $cat->active = 0;
            $cat->save();

            return response()->json([
                'message' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'categories not found or deleted'
            ], 404);
        }
    }


    //update de registros
    public function updateCategoriDestination(CategoryDestinationContentsCreateRequest $request)
    {
        $catContenido = new categoryDestinationContent;
        //categoryDestinationContent::where('id', $request->id)
        $catContenido->where('id', $request->id)
            ->update([
                "url" => $request->url,
                "description" => $request->description,
                "description_footer" => $request->description_footer,
                "meta_title" => $request->meta_title,
                "meta_description" => $request->meta_description,
                "meta_keywords" => $request->meta_keywords
            ]);




        $user = categoryDestinationContent::find($request->id);
        /*$res = categoryDestinationContent::select("category_destination_contents.*", ".*")
            ->join('', '.category_destination_content_id', '=', 'category_destination_contents.id')
            ->where('category_destination_contents.id', $request->id)
            ->where('.language_id', $request->idioma)
            ->get();*/
        $res = categoryDestinationContent::select("*")
            ->join('link_table', '.category_destination_content_id', '=', 'category_destination_contents.id')
            ->where('category_destination_contents.id', $request->id)
            ->where('link_table.language_id', $request->idioma)
            ->get();

        foreach ($res as $item) {

            $user->destination()->updateExistingPivot($item->destination_id, [
                'destination_id' => $request->destino,
            ]);
            $user->category()->updateExistingPivot($item->category_id, [
                'category_id' => $request->categoria,
            ]);
        }

        return response()->json([
            'message' => 'success'
        ], 200);
    }



    function getAllCategoriDestinationCMS()
    {
        $res = categoryDestinationContent::select("*")
            ->join('link_table', '.category_destination_content_id', '=', 'category_destination_contents.id')
            ->where('category_destination_contents.active', 1)
            ->orderBy('url', 'ASC')
            ->get();
        /*
        //anterior
        $res = categoryDestinationContent::select("category_destination_contents.*", ".*")
            ->join('', '.category_destination_content_id', '=', 'category_destination_contents.id')
            ->where('category_destination_contents.active', 1)
            ->orderBy('url', 'ASC')
            ->get();
        */

        /*
        $res = categoryDestinationContent::select(
            "*",
        )->where('active', 1)
            ->orderBy('url', 'ASC')
            ->get();
        */
        //->toSql();
        //$res = categoryDestinationContent::with('espIdiomaSolo')->where('active', 1)->get();

        return $res;
    }

}
