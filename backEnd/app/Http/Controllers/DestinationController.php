<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DestinationCreateRequest;
use Illuminate\Support\Facades\Storage;
use App\destination;
use App\destinationContent;
use App\tour;

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
            //$this->addCategories($request);
            $this->addDestination($request);
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



    public function getAllDestinationCMS(Request $request)
    {

        /*$categorias = ::select("category_contents.category_id", "category_contents.name")
            ->join('category_contents', 'category_id', '=', 'categories.id')
            ->where('categories.active', 1)
            ->where('category_contents.language_id', 1)
            ->orderBy('category_contents.name', 'DESC')
            ->get();
        */


        $destinations = Destination::with(['destinationContentEsp','tours'])->get();

        $destinations_back=[];
        $tours_checked=[];
        foreach($destinations as $destination){

            //dump($destination->destinationContentEsp);
            //dump('--------');

            if(!empty($destination->destinationContentEsp) && count($destination->destinationContentEsp)>0){
                array_push($destinations_back,[
                    'id'=>$destination->id,
                    'name'=>$destination->destinationContentEsp[0]['name']
                ]);
            }

            $tours=$destination->tours->toArray();

            //dump($tours);

            $pos = array_search($request->id, array_column($tours,'id'));
            //dump($pos);
            if($pos!==false){
                array_push($tours_checked,$destination->id);
            }
        }

        return response()->json([
            'status'=>'OK',
            'destinations'=>$destinations_back,
            'checked'=>$tours_checked
        ]);




        /*

            $existentes = categoryTour::select("*")
                ->where('tour_id', $request->id)
                ->get();

            $checado = [];
            $cont = 0;
            $aux = json_decode($existentes, true);
            for ($a = 0; $a < sizeof($categorias); $a++) {
                $key = array_search($categorias[$a]["category_id"], array_column($aux, 'category_id'));
                if ($key !== false) {
                    //$checado[$cont]["id"] = $categorias[$a]["id"];
                    //$checado[$cont]["name"] = $categorias[$a]["name"];
                    //$cont++;
                    $checado[] = $a;
                }
                //$arr[$a]["id"] = $categorias[$a]["id"];
                //$arr[$a]["name"] = $categorias[$a]["name"];
                //$arr[$a]["active"] = ($key === false) ? false : true;
            }
            $res = [];
            $res[0]["categoria"] = $categorias;
            $res[0]["checado"] = $checado;
            $res[0]["existe"] = $existentes;
            return json_encode($res);
        */
    }

    function addRemoveDestinationTour(Request $request)
    {

        $tour= tour::find($request->idTour);


        $tour->destinations()->sync($request->destinations);

        return response()->json([
            'status'=>'OK'
        ]);

        /*
            $idLocalizado = $request->idTour;
            if ($idLocalizado == 0) {
                $res = tour::select("id")
                    ->where('clave', $request->claveSend)
                    ->first();
                if ($res) {
                    //$idLocalizado = $res->id;
                    $this->procesoCateTour($request->idCategoria, $res->id);
                } else {
                    return response()->json([
                        'message' => 'Debe crear un tour previo a realizar esta acción'
                    ], 400);
                }
            } else {
                $this->procesoCateTour($request->idCategoria, $request->idTour);
            }

        /*
            //si es igual a cero quiere decir que es un nuevo registro
            if ($request->idTour == 0) {
                //valida si ya esta registrado la clave en las tablas de tour content
                $res = tourContent::select(
                    "tour_contents.tour_id"
                )
                    ->join('tours', 'tours.id', '=', 'tour_contents.tour_id')
                    ->where('tours.active', 1)
                    ->where('tours.clave', $request->claveSend)
                    ->get();

                if (sizeof($res) === 0) {

                    return response()->json([
                        'message' => 'Debe crear un tour previo a realizar esta acción'
                    ], 400);
                } else {
                    $this->procesoCateTour($request->idCategoria, $res[0]->tour_id);
                }
            } else {
                //de lo contrario mando a ejecutar el proceso del alta o baja del registro
                $this->procesoCateTour($request->idCategoria, $request->idTour);
            }
        */
    }
}
