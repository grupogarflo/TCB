<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DestinationCreateRequest;
use Illuminate\Support\Facades\Storage;
use App\destination;
use App\destinationContent;




class DestinationController extends Controller
{
    //recuepra todas las categorias
    function getAllDestination()
    {
        $res = destinationContent::select("destination_id as id", "destination_contents.name", "destination_contents.url", "languages.name as idioma", "destination_contents.img")
            ->join('destinations', 'destinations.id', '=', 'destination_contents.destination_id')
            ->join('languages', 'languages.id', '=', 'destination_contents.language_id')
            ->where('destinations.active', 1)
            ->where('languages.id', 1)
            ->orderBy('destination_contents.name', 'ASC')
            ->get();
        //->toSql();
        return $res;
    }

    //agrega nuevas categories
    function addDestination(DestinationCreateRequest $request)
    {
        //valido si existe la clave enviada para recuperar el id o caso contrario insertar uno nuevo
        $res = destination::where('clave', '=', $request->clave)->first();
        if ($res === null) {
            $destino = new destination;
            $destino->clave = $request->clave;
            $destino->save();
            //ultimo id insertado
            $ultimoID = $destino->id;
        } else {
            $ultimoID = $res->id;
        }

        $desContenido = new destinationContent;
        $desContenido->name = $request->name;
        $desContenido->title = $request->title;
        $desContenido->url = $request->url;
        $desContenido->show_home = ($request->show_home) ? 1 : 0;
        $desContenido->img = "n/a";
        $desContenido->description = $request->description;
        $desContenido->description_footer = $request->description_footer;
        $desContenido->meta_title = $request->meta_title;
        $desContenido->meta_description = $request->meta_description;
        $desContenido->meta_keywords = $request->meta_keywords;
        $desContenido->destination_id = $ultimoID;
        $desContenido->language_id = $request->idioma;
        $desContenido->save();

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    //update de categorias
    public function updateDestination(DestinationCreateRequest $request)
    {
        //esto es por si por error solo dieron de alta un solo idioma y quieren agregar el nuevo registros
        //desde la ediicion del registro actual
        //se valida si existe el registro por medio del id del destino y el id del idioma
        //si no existe entonces se recupera la clave del destino y se pisa la clave que se manda desde vue
        //para mandarla a la funcion del agregado
        $res = destinationContent::select("*")
            ->where('destination_id', $request->id)
            ->where('language_id', $request->idioma)
            ->get();
        if (count($res) === 0) {
            $info = destination::where("id", $request->id)->first();
            $request->clave = $info->clave;
            $this->addCategories($request);
        } else {
            destinationContent::where('destination_id', $request->id)
                ->where('language_id', $request->idioma)
                ->update([
                    "name" => $request->name,
                    "title" => $request->title,
                    "url" => $request->url,
                    "show_home" => ($request->show_home) ? 1 : 0,
                    "description" => $request->description,
                    "description_footer" => $request->description_footer,
                    "meta_title" => $request->meta_title,
                    "meta_description" => $request->meta_description,
                    "meta_keywords" => $request->meta_keywords
                ]);
        }
        return response()->json([
            'message' => 'success'
        ], 200);
    }

    //elimina categorias
    function deleteDestination(Request $request)
    {
        try {
            $cat = destination::find($request->id);
            $cat->active = 0;
            $cat->save();

            return response()->json([
                'message' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'destiantion not found or deleted'
            ], 404);
        }
    }

    //recupera la informacion de la categoria por idioma
    function getDestinationInfo(Request $request)
    {
        $res = destinationContent::select("*")
            ->where('destination_id', $request->id)
            ->where('language_id', $request->idioma)
            ->get();
        //->toSql();
        if (count($res) > 0) {
            return $res;
        } else {
            return response()->json([
                'message' => 'destination not found or deleted'
            ], 404);
        }
    }

    function addDestinationImg(Request $request)
    {

        $idLocalizado = $request->id;

        //valido si el id viene en cero entonces tengo que localizar el id por meido de la clave que se envia en el campo
        //clave
        //en caso de que el id venga con algun valor entonces se hace el proceso normal de subir la foto y
        //actualizar el campo de la img en la base de datos
        if ($idLocalizado == 0) {
            $res = destination::select("id")
                ->where('clave', $request->clave)
                ->first();
            if ($res) {
                $idLocalizado = $res->id;
            } else {
                return response()->json([
                    'message' => 'destiantion id not found or deleted'
                ], 404);
            }
        }

        try {
            //$url = Storage::disk()->put('destinos', $request->file('image'));
            $url =
                $request->file('image')->storeAs(
                    'destinos',
                    $request->file('image')->getClientOriginalName()
                );
            destinationContent::where('destination_id', $idLocalizado)
                ->update([
                    "img" => $url
                ]);
            return response()->json([
                'message' => 'success'
            ], 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json([
                'message' => 'error update destination img'
            ], 404);
        }
    }
}
