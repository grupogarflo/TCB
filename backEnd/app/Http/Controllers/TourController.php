<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TourCreateRequest;
use Illuminate\Support\Facades\Storage;
use App\tour;
use App\tourContent;
use App\priceTour;
use App\category;
use App\categoryTour;
use App\Suggestion;
use App\SuggestionTour;
use App\Gallery;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ToursExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function GuzzleHttp\json_decode;

class TourController extends Controller
{
    //recuepra todas las categorias
    function getAllTour()
    {

        $res = tourContent::select(
            "tour_contents.name",
            "tour_contents.url",
            "languages.name as idioma",
            "tour_contents.img",
            "tour_contents.banner",
            "tour_contents.map",
            "tour_contents.gallery",
            "tour_contents.video",
            "tours.id",
            "tours.public",
            "tours.checkin_payment"
        )
            ->join('tours', 'tour_contents.tour_id', '=', 'tours.id')
            ->join('languages', 'languages.id', '=', 'tour_contents.language_id')
            ->where('tours.active', 1)
            ->where('languages.id', 1)
            ->orderBy('tour_contents.name', 'ASC')
            ->get();
        //->toSql();




        $arr = [];
        for ($a = 0; $a < sizeof($res); $a++) {
            $arr[$a]["name"] = $res[$a]->name;
            $arr[$a]["url"] = $res[$a]->url;
            $arr[$a]["idioma"] = $res[$a]->idioma;
            $arr[$a]["img"] = $res[$a]->img;
            $arr[$a]["banner"] = $res[$a]->banner;
            $arr[$a]["map"] = $res[$a]->map;
            $arr[$a]["gallery"] = $res[$a]->gallery;
            $arr[$a]["video"] = $res[$a]->video;
            $arr[$a]["id"] = $res[$a]->id;
            $arr[$a]["publicoShow"] = ($res[$a]->public === 1) ? "Si" : "No";
            $arr[$a]["public"] = $res[$a]->public;
            $arr[$a]["full_photo_path"] = env('APP_URL') . '/storage' . $res[$a]->img;
            $arr[$a]["checkin_payment"] = $res[$a]->checkin_payment;
        }


        return json_encode($arr);
        //return $res;
    }

    //agrega nuevas categories
    function addTour(TourCreateRequest $request)
    {
        //valido si existe la clave enviada para recuperar el id o caso contrario insertar uno nuevo
        $res = tour::where('clave', '=', $request->clave)->first();
        if ($res === null) {
            $destino = new tour;
            $destino->clave = $request->clave;
            $destino->save();
            //ultimo id insertado
            $ultimoID = $destino->id;
        } else {
            $ultimoID = $res->id;
        }

        $desContenido = new tourContent();
        $desContenido->name = $request->name;
        $desContenido->sub_title = $request->sub_title;
        $desContenido->url = $request->url;
        $desContenido->rank = ($request->rank == '') ? 0 : $request->rank;
        $desContenido->img = "n/a";
        $desContenido->top_home = ($request->top_home) ? 1 : 0;
        $desContenido->description_small = ($request->description_small == '') ? '' : $request->description_small;
        $desContenido->description = $request->description;
        $desContenido->not_include = $request->not_include;
        $desContenido->avaible = $request->avaible;
        $desContenido->duration = $request->duration;
        $desContenido->includes = $request->includes;
        $desContenido->map = "n/a";
        $desContenido->gallery = "n/a";
        $desContenido->video = "n/a";
        $desContenido->bring = $request->bring;
        $desContenido->note = $request->note;
        $desContenido->meta_title = $request->meta_title;
        $desContenido->meta_description = $request->meta_description;
        $desContenido->meta_keywords = $request->meta_keywords;
        $desContenido->banner = "n/a";
        $desContenido->peek_id = $request->peek_id;
        $desContenido->tour_id = $ultimoID;
        $desContenido->language_id = $request->idioma;
        $desContenido->order_home = $request->homeOrder;
        $desContenido->ventrata_product_id = $request->ventrata;
        $desContenido->ventrata_option_id = $request->ventrata_option_id;
        $desContenido->save();

        //ultimo id insertado
        $IDTourInsert = $desContenido->id;

        /*
        $precio = new priceTour();
        $precio->price_fake_adult = $request->price_fake_adult;
        $precio->price_fake_child = $request->price_fake_child;
        $precio->price_real_adult = $request->price_real_adult;
        $precio->price_real_child = $request->price_real_child;
        $precio->tour_contents_id = $IDTourInsert;
        $precio->special_price    = ($request->special_price) ? 1 : 0;
        $precio->save();
        */

        /*
        //valido si no existe registro insertados de icons para el tour
        $res = SuggestionTour::where('tours_id', '=', $ultimoID)->get();
        if (count($res) === 0) {
            //ingresa los icons del tour
            foreach ($request->icons as $value) {
                $suggestion = new SuggestionTour();
                $suggestion->suggestions_id = $value;
                $suggestion->tours_id = $ultimoID;
                $suggestion->save();
            }
        }
        */

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    //update de categorias
    public function updateTour(TourCreateRequest $request)
    {
        //esto es por si por error solo dieron de alta un solo idioma y quieren agregar el nuevo registros
        //desde la ediicion del registro actual
        //se valida si existe el registro por medio del id del tour y el id del idioma
        //si no existe entonces se recupera la clave del tour y se pisa la clave que se manda desde vue
        //para mandarla a la funcion del agregado
        $infoTourResponse = tourContent::select("*")
            ->where('tour_id', $request->id)
            ->where('language_id', $request->idioma)
            ->get();

        if (count($infoTourResponse) === 0) {
            $info = tour::where("id", $request->id)->first();
            $request->clave = $info->clave;
            $this->addTour($request);
        } else {
            tourContent::where('tour_id', $request->id)
                ->where('language_id', $request->idioma)
                ->update([
                    "name" => $request->name,
                    "sub_title" => $request->sub_title,
                    "url" => $request->url,
                    "rank" => ($request->rank == '') ? 0 : $request->rank,
                    "top_home" => ($request->top_home) ? 1 : 0,
                    "description_small" => ($request->description_small == '') ? '' : $request->description_small,
                    "description" => $request->description,
                    "not_include" => $request->not_include,
                    "avaible" => $request->avaible,
                    "duration" => $request->duration,
                    "includes" => $request->includes,
                    "bring" => $request->bring,
                    "note" => $request->note,
                    "meta_title" => $request->meta_title,
                    "meta_description" => $request->meta_description,
                    "meta_keywords" => $request->meta_keywords,
                    "peek_id" => $request->peek_id,
                    "order_home" => $request->homeOrder,
                    "ventrata_product_id" => $request->ventrata,
                    "ventrata_option_id" => $request->ventrata_option_id

                ]);
            /*
            priceTour::where('tour_contents_id', $infoTourResponse[0]->id)
                ->update([
                    "price_fake_adult" => $request->price_fake_adult,
                    "price_fake_child" => $request->price_fake_child,
                    "price_real_adult" => $request->price_real_adult,
                    "price_real_child" => $request->price_real_child,
                    "special_price"    => ($request->special_price) ? 1 : 0
                ]);
            */

            /*
            //elimino los icons del tour
            $del = new SuggestionTour();
            $del->where('tours_id', $request->id)->delete();

            //actualiza los icons del tour
            foreach ($request->icons as $value) {
                $suggestion = new SuggestionTour();
                $suggestion->suggestions_id = $value;
                $suggestion->tours_id = $request->id;
                $suggestion->save();
            }
            */
        }
        return response()->json([
            'message' => 'success'
        ], 200);
    }

    //elimina categorias
    function deleteTour(Request $request)
    {
        try {
            $cat = tour::find($request->id);
            $cat->active = 0;
            $cat->public = 0;
            $cat->save();

            return response()->json([
                'message' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'tour not found or deleted'
            ], 404);
        }
    }

    //recupera la informacion de la categoria por idioma
    function getTourInfo(Request $request)
    {
        /*
        $res = tourContent::select(

            "tour_contents.*",
            "price_tours.*",
            "tours.*"
        )
            ->join('price_tours', 'tour_contents.id', '=', 'price_tours.tour_contents_id')
            ->join('tours', 'tour_contents.tour_id', '=', 'tours.id')
            ->where('tour_contents.tour_id', $request->id)
            ->where('tour_contents.language_id', $request->idioma)
            ->get();
        //->toSql();
        */
        $res = tourContent::select(

            "tour_contents.*",
            "tours.*"
        )
            ->join('tours', 'tour_contents.tour_id', '=', 'tours.id')
            ->where('tour_contents.tour_id', $request->id)
            ->where('tour_contents.language_id', $request->idioma)
            ->get();
        //->toSql();


        if (count($res) > 0) {
            return $res;
        } else {
            return response()->json([
                'message' => 'tours not found or deleted'
            ], 404);
        }
    }

    function addImgTour(Request $request)
    {

        $idLocalizado = $request->id;

        //valido si el id viene en cero entonces tengo que localizar el id por meido de la clave que se envia en el campo
        //clave
        //en caso de que el id venga con algun valor entonces se hace el proceso normal de subir la foto y
        //actualizar el campo de la img en la base de datos
        if ($idLocalizado == 0) {
            $res = tour::select("id")
                ->where('clave', $request->clave)
                ->first();
            if ($res) {
                $idLocalizado = $res->id;
            } else {
                return response()->json([
                    'message' => 'tour id not found or deleted'
                ], 404);
            }
        }

        try {
            //$url = Storage::disk()->put('tour', $request->file('image'));
            /*$url =
                $request->file('image')->storeAs(
                    'tour',
                    $request->file('image')->getClientOriginalName()
                );
            */

            if ($request->hasFile('image')) {
                $timeStamp = uniqid();
                $url = '/tour/' . $timeStamp . '_' . $request->file('image')->getClientOriginalName();

                Storage::disk('public')->put($url, File::get($request->file('image')));
            }


            tourContent::where('tour_id', $idLocalizado)
                ->update([
                    "img" => $url
                ]);
            return response()->json([
                'message' => 'success'
            ], 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json([
                'message' => 'error update tour img'
            ], 404);
        }
    }

    function addMapTour(Request $request)
    {
        // se valida para saber si se recupera el id por medio de la clave o se toma el id que se envia, pues si
        // es cero quiere decir que se esta dado de alta el tour.
        $idLocalizado = $request->id;

        if ($idLocalizado == 0) {
            $res = tour::select("id")
                ->where('clave', $request->clave)
                ->first();
            if ($res) {
                $idLocalizado = $res->id;
            } else {
                return response()->json([
                    'message' => 'tour id not found or deleted'
                ], 404);
            }
        }

        try {

            tourContent::where('tour_id', $idLocalizado)
                ->update([
                    "map" => $request->mapa
                ]);
            return response()->json([
                'message' => 'success'
            ], 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json([
                'message' => 'error update tour map'
            ], 404);
        }
    }

    function addGaleriaTour(Request $request)
    {
        // se valida para saber si se recupera el id por medio de la clave o se toma el id que se envia, pues si
        // es cero quiere decir que se esta dado de alta el tour.
        $idLocalizado = $request->id;

        if ($idLocalizado == 0) {
            $res = tour::select("id")
                ->where('clave', $request->clave)
                ->first();
            if ($res) {
                $idLocalizado = $res->id;
            } else {
                return response()->json([
                    'message' => 'tour id not found or deleted'
                ], 404);
            }
        }

        try {

            tourContent::where('tour_id', $idLocalizado)
                ->update([
                    "gallery" => $request->mapa
                ]);
            return response()->json([
                'message' => 'success'
            ], 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json([
                'message' => 'error update tour map'
            ], 404);
        }
    }

    function getCategoriaTour(Request $request)
    {

        $categorias = category::select("category_contents.category_id", "category_contents.name")
            ->join('category_contents', 'category_id', '=', 'categories.id')
            ->where('categories.active', 1)
            ->where('category_contents.language_id', 1)
            ->orderBy('category_contents.name', 'DESC')
            ->get();

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
    }

    function addRemoveCatTour(Request $request)
    {
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

    function procesoCateTour($idCategoria, $idTour)
    {
        //valido si existe la clave enviada para recuperar el id o caso contrario insertar uno nuevo
        //$res = categoryTour::select("category_id")where('category_id', '=', $idCategoria)->get();
        $res = categoryTour::select("category_id")
            ->where('category_id', $idCategoria)
            ->where('tour_id', $idTour)
            ->first();
        if ($res) {
            categoryTour::where('category_id', $idCategoria)
                ->where('tour_id', $idTour)
                ->delete();
        } else {
            $agrega = new categoryTour();
            $agrega->category_id = $idCategoria;
            $agrega->tour_id = $idTour;
            $agrega->save();

            //return $idCategoria . $idTour;
        }
        return response()->json([
            'message' => 'success'
        ], 200);
    }

    function removePublic(Request $request)
    {/*
        tour::where('id', $request->id)
            ->update([
                "public" => ($request->public === 1) ? 0 : 1
            ]);
            */

        try {
            tour::where('id', $request->id)
                ->update([
                    "public" => ($request->public === 1) ? 0 : 1
                ]);

            return response()->json([
                'message' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'tour not found or deleted'
            ], 404);
        }
    }


    function getIcons(Request $request)
    {
        $arr = [];
        $checado = [];

        $res = Suggestion::select("*")
            ->where('active', 1)
            ->get();

        $existentes = SuggestionTour::select(
            "suggestion_tours.*",
            "suggestions.*"
        )
            ->join('suggestions', 'suggestion_tours.suggestions_id', '=', 'suggestions.id')
            ->where('suggestion_tours.tours_id', $request->id)
            ->where('suggestions.active', 1)
            ->orderBy('suggestions.name_esp', 'ASC')
            ->get();
        //->toSql();

        $aux = json_decode($existentes, true);
        for ($a = 0; $a < sizeof($res); $a++) {
            $key = array_search($res[$a]["id"], array_column($aux, 'suggestions_id'));
            if ($key !== false) {
                //$checado[] = $a;
                $checado[] = $res[$a]->id;
            }
        }

        $arr[0]["tours"] = $res;
        $arr[0]["checado"] = $checado;
        return json_encode($arr);
    }

    function addRemoveIconTour(Request $request)
    {
        $idLocalizado = $request->idTour;
        if ($idLocalizado == 0) {
            $res = tour::select("id")
                ->where('clave', $request->claveSend)
                ->first();
            if ($res) {
                //$idLocalizado = $res->id;
                $this->procesoIconTour($request->idCategoria, $res->id);
            } else {
                return response()->json([
                    'message' => 'Debe crear un tour previo a realizar esta acción'
                ], 400);
            }
        } else {
            $this->procesoIconTour($request->idCategoria, $request->idTour);
        }
    }

    function procesoIconTour($idCategoria, $idTour)
    {
        //valido si existe la clave enviada para recuperar el id o caso contrario insertar uno nuevo
        //$res = categoryTour::select("category_id")where('category_id', '=', $idCategoria)->get();
        $res = SuggestionTour::select("suggestions_id")
            ->where('suggestions_id', $idCategoria)
            ->where('tours_id', $idTour)
            ->first();
        if ($res) {
            SuggestionTour::where('suggestions_id', $idCategoria)
                ->where('tours_id', $idTour)
                ->delete();
        } else {
            $agrega = new SuggestionTour();
            $agrega->suggestions_id = $idCategoria;
            $agrega->tours_id = $idTour;
            $agrega->save();

            //return $idCategoria . $idTour;
        }
        return response()->json([
            'message' => 'success'
        ], 200);
    }


    function addImgTourBanner(Request $request)
    {

        $idLocalizado = $request->id;

        //valido si el id viene en cero entonces tengo que localizar el id por meido de la clave que se envia en el campo
        //clave
        //en caso de que el id venga con algun valor entonces se hace el proceso normal de subir la foto y
        //actualizar el campo de la img en la base de datos
        if ($idLocalizado == 0) {
            $res = tour::select("id")
                ->where('clave', $request->clave)
                ->first();
            if ($res) {
                $idLocalizado = $res->id;
            } else {
                return response()->json([
                    'message' => 'tour id not found or deleted'
                ], 404);
            }
        }

        try {
            //$url = Storage::disk()->put('tour', $request->file('image'));
            $url =
                $request->file('image')->storeAs(
                    'tour',
                    $request->file('image')->getClientOriginalName()
                );
            tourContent::where('tour_id', $idLocalizado)
                ->update([
                    "banner" => $url
                ]);
            return response()->json([
                'message' => 'success'
            ], 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json([
                'message' => 'error update tour img banner'
            ], 404);
        }
    }


    //precios de tours

    function getPriceTour(Request $request)
    {

        $res = priceTour::select(
            "*"
        )
            ->where('tour_contents_id', $request->id)
            ->get();
        //->toSql();

        if (count($res) > 0) {
            return $res;
        } else {
            return response()->json([
                'message' => 'tours not found or deleted'
            ], 404);
        }
    }

    function addPriceTour(Request $request)
    {
        $res = tour::where('clave', '=', $request->clave)->first();
        if ($res === null) {
            return response()->json([
                'message' => 'none tour selected'
            ], 400);
        } else {
            $ultimoID = $res->id;
        }

        $precio = new priceTour();
        $precio->price_fake_adult = $request->price_fake_adult;
        $precio->price_fake_child = $request->price_fake_child;
        $precio->price_real_adult = $request->price_real_adult;
        $precio->price_real_child = $request->price_real_child;
        $precio->tour_contents_id = $ultimoID;
        $precio->special_price    = ($request->special_price) ? 1 : 0;
        $precio->save();

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    function updatePriceTour(Request $request)
    {

        $res = priceTour::where('tour_contents_id', '=', $request->id)->first();
        if ($res === null) {
            $precio = new priceTour();
            $precio->price_fake_adult = $request->price_fake_adult;
            $precio->price_fake_child = $request->price_fake_child;
            $precio->price_real_adult = $request->price_real_adult;
            $precio->price_real_child = $request->price_real_child;
            $precio->tour_contents_id = $request->id;
            $precio->special_price    = ($request->special_price) ? 1 : 0;
            $precio->save();
        } else {
            priceTour::where('tour_contents_id', $request->id)
                ->update([
                    "price_fake_adult" => $request->price_fake_adult,
                    "price_fake_child" => $request->price_fake_child,
                    "price_real_adult" => $request->price_real_adult,
                    "price_real_child" => $request->price_real_child,
                    "special_price"    => ($request->special_price) ? 1 : 0
                ]);
        }





        return response()->json([
            'message' => 'success'
        ], 200);
    }


    //video

    function getVideoTour(Request $request)
    {
        $res = tourContent::select(
            "video"
        )
            ->where('tour_id', $request->id)
            ->get();
        //->toSql();

        if (count($res) > 0) {
            return $res;
        } else {
            return response()->json([
                'message' => 'tours not found or deleted'
            ], 404);
        }
    }

    function updateVideoTour(Request $request)
    {
        if ($request->clave != '') {
            $res = tour::where('clave', '=', $request->clave)->first();
            if ($res === null) {
                return response()->json([
                    'message' => 'none tour selected'
                ], 400);
            } else {
                $ultimoID = $res->id;
            }
        } else {
            $ultimoID = $request->id;
        }


        tourContent::where('tour_id', $ultimoID)
            ->update([
                "video" => $request->video,
            ]);

        return response()->json([
            'message' => 'success'
        ], 200);
    }


    //gallerias
    function addGalleyTour(Request $request)
    {
        $idLocalizado = $request->id;
        if ($idLocalizado == 0) {
            $res = tour::select("id")
                ->where('clave', $request->clave)
                ->first();
            if ($res) {
                $idLocalizado = $res->id;
            } else {
                return response()->json([
                    'message' => 'tour id not found or deleted'
                ], 404);
            }
        }
        try {

            foreach ($request->file('image') as $img) {
                $Gallery = new Gallery();
                /*$Gallery->img =
                    $img->storeAs(
                        'gallery',
                        $img->getClientOriginalName()
                    );*/
                $timeStamp = uniqid();
                $url = '/tour/' . $idLocalizado . '/' . $timeStamp . '_' . $img->getClientOriginalName();

                Storage::disk('public')->put($url, File::get($img));

                $Gallery->img = $url;


                $Gallery->tour_id = $idLocalizado;
                $Gallery->order = $request->order ?? null;
                $Gallery->save();
            }

            //$url = Storage::disk()->put('tour', $request->file('image'));
            //$url =
            //    $request->file('image')->storeAs(
            //        'tour',
            //        $request->file('image')->getClientOriginalName()
            //    );

            return response()->json([
                'message' => 'success insert'
            ], 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json([
                'message' => 'error insert tour img'
            ], 404);
        }
        /*
        //valido si el id viene en cero entonces tengo que localizar el id por meido de la clave que se envia en el campo
        //clave
        //en caso de que el id venga con algun valor entonces se hace el proceso normal de subir la foto y
        //actualizar el campo de la img en la base de datos
        //if ($idLocalizado == 0) {
        $res = tour::select("id")
            ->where('clave', $request->clave)
            ->first();
        if ($res) {
            try {
                //$url = Storage::disk()->put('tour', $request->file('image'));
                //$url =
                //    $request->file('image')->storeAs(
                //        'tour',
                //        $request->file('image')->getClientOriginalName()
                //    );

                foreach ($request->file('image') as $img) {

                    Gallery::where('tour_id', $idLocalizado)
                        ->update([
                            "img" =>  $img->storeAs(
                                'tour',
                                $img->getClientOriginalName()
                            )
                        ]);
                }

                return response()->json([
                    'message' => 'success update'
                ], 200);
            } catch (\Illuminate\Database\QueryException $ex) {
                return response()->json([
                    'message' => 'error update tour img'
                ], 404);
            }
        } else {

            try {

                foreach ($request->file('image') as $img) {
                    $Gallery = new Gallery();
                    $Gallery->img =
                        $img->storeAs(
                            'tour',
                            $img->getClientOriginalName()
                        );
                    $Gallery->tour_id = $idLocalizado;
                    $Gallery->save();
                }

                //$url = Storage::disk()->put('tour', $request->file('image'));
                //$url =
                //    $request->file('image')->storeAs(
                //        'tour',
                //        $request->file('image')->getClientOriginalName()
                //    );

                return response()->json([
                    'message' => 'success insert'
                ], 200);
            } catch (\Illuminate\Database\QueryException $ex) {
                return response()->json([
                    'message' => 'error insert tour img'
                ], 404);
            }
        }
        */
    }

    function getGalleyTour(Request $request)
    {
        $idLocalizado = $request->id;

        //valido si el id viene en cero entonces tengo que localizar el id por meido de la clave que se envia en el campo
        //clave
        //en caso de que el id venga con algun valor entonces se hace el proceso normal de subir la foto y
        //actualizar el campo de la img en la base de datos
        if ($idLocalizado == 0) {
            $res = tour::select("id")
                ->where('clave', $request->clave)
                ->first();
            if ($res) {
                $idLocalizado = $res->id;
            } else {
                return response()->json([
                    'message' => 'tour id not found or deleted'
                ], 404);
            }
        }

        $res = Gallery::where('tour_id', $idLocalizado)->get();
        if ($res !== null) {
            return response()->json([
                'data' => $res,
                'message' => ''
            ], 200);
        } else {
            return response()->json([
                'data' => '',
                'message' => 'info not found or deleted'
            ], 404);
        }
    }

    function deleteGalleyTour(Request $request)
    {
        //borra fisicamente el archivo
        /*$image_path = '../../public_html/assets/images/' . $request->url;
        if (file_exists($image_path)) {

            unlink($image_path);
            Gallery::where('id', $request->id)
                ->delete();*/


        $img = Gallery::find($request->id);

        //dd($img);
        if (File::exists(public_path('/storage/' . $img->img))) {

            File::delete(public_path('/storage/' . $img->img));

            $img->delete();
            return response()->json([
                'message' => 'success'
            ], 200);
        } else {
            return response()->json([
                'message' => 'img no existe o ya se elimino'
            ], 404);
        }
    }



    public function exportTours()
    {

        $res = tourContent::all();




        $columns = [];
        $columns[] = ['name' => 'Nombre'];
        $columns[] = ['name' => 'Url'];
        $columns[] = ['name' => 'Idioma'];
        $columns[] = ['name' => 'Subtitulo'];
        $columns[] = ['name' => 'Rank'];
        $columns[] = ['name' => 'Disponoble'];
        $columns[] = ['name' => 'Privado'];
        $columns[] = ['name' => 'Duracion'];
        $columns[] = ['name' => 'Publicado'];

        $columns[] = ['name' => 'Tarifas Privado'];

        $columns[] = ['name' => 'Precio Fake Adulto'];
        $columns[] = ['name' => 'Precio Real Adulto'];
        $columns[] = ['name' => 'Precio Fake Niño'];
        $columns[] = ['name' => 'Precio Real Niño'];

        foreach ($res as $r) {

            if ($r->is_private) {
                /// rates to private tours

                $rates = DB::table('private_rates')->where('tour_id', $r->tour_id)->get();



                $ratesToShow = [];

                foreach ($rates as $rate) {
                    $rate_from_fake_mxn = $this->usdToMxn($rate->rate_from_fake);
                    $rate_from_real_mxn = $this->usdToMxn($rate->rate_from_real);

                    $rate_fake_price_mxn = $this->usdToMxn($rate->fake_price);
                    $rate_real_price_mxn = $this->usdToMxn($rate->real_price);

                    $paxName  = DB::table('pax_ranges')->where('id', $rate->pax_range_id)->first();
                    array_push($ratesToShow, [
                        'rate_from_fake' => $rate->rate_from_fake,
                        'rate_from_real' => $rate->rate_from_real,
                        'fake_price' => $rate->fake_price,
                        'real_price' => $rate->real_price,
                        'pax' => $paxName->name_esp


                    ]);

                    $r->discount = ($rate_from_fake_mxn > 0 && $rate_from_real_mxn) ? (($rate_from_fake_mxn - $rate_from_real_mxn) * 100) / $rate_from_fake_mxn : 0;
                }

                $r->rates = $ratesToShow;
            } else {

                $rateNotPrivate = DB::table('price_tours')->where('tour_contents_id', $r->id)->first();


                //Log::info('no private' , [$rateNotPrivate]);
                if (!empty($rateNotPrivate)) {
                    $r->fake_adult = $rateNotPrivate->price_fake_adult;
                    $r->real_adult = $rateNotPrivate->price_real_adult;
                    $r->price_fake_child_mxn = $rateNotPrivate->price_fake_child;
                    $r->price_real_child_mxn =  $rateNotPrivate->price_real_child;
                } else {
                    $r->fake_adult = 0;
                    $r->real_adult = 0;
                    $r->price_fake_child_mxn = 0;
                    $r->price_real_child_mxn =  0;
                }
            }
        }

        $file_name = date('YmdHis') . rand() . "_Tours";

        //Log ::info('export info', [$res]);



        Excel::store(new ToursExport($res, $columns), 'excelExport/' . $file_name . '.xlsx', 'local');
        return response()->json(['file_name' => $file_name . '.xlsx']);
    }


    public function downloadExport($name)
    {

        //dd($name);
        return response()->download(Storage::path('excelExport/' . $name))->deleteFileAfterSend(true);
    }
    //to add o remove flag of the data base for checkin payment od the tour in the front
    function addRemoveCheckinPayment(Request $request)
    {

        try {
            tour::where('id', $request->id)
                ->update([
                    "checkin_payment" => $request->data
                ]);

            return response()->json([
                'message' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'can`t update checkin payment tour please verificate'
            ], 404);
        }
    }
}
