<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DestinationCreateRequest;
use Illuminate\Support\Facades\Storage;
use App\destination;
use App\destinationContent;
use App\tour;
use App\tourContent;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

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
        $desContenido->order = $request->order;
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
                    "order" => $request->order,
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
        $res = destinationContent::where('destination_id', $request->id)
            ->where('language_id', $request->idioma)
            ->get();
        //->toSql();

        foreach ($res as $r) {
            //dump($r);
            $r['order'] = ($r['order'] === 999) ? '' : $r['order'];
        }

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

            /*$request->file('image')->storeAs(
                    'destinos',
                    $request->file('image')->getClientOriginalName()
                );*/

            //dump('test');
            if ($request->hasFile('image')) {
                $timeStamp = uniqid();
                $url = '/destinations/' . $timeStamp . '_' . $request->file('image')->getClientOriginalName();

                Storage::disk('public')->put($url, File::get($request->file('image')));
            }

            //dd($url);
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


        $destinations = Destination::with(['destinationContentEsp', 'tours'])->get();

        $destinations_back = [];
        $tours_checked = [];

        //dump($destinations);
        foreach ($destinations as $destination) {


            // \Log::info('destinations id , spa',[$destination->id, $destination->destinationContentEsp]);
            //dump('--------');

            if (!empty($destination->destinationContentEsp)) {
                array_push($destinations_back, [
                    'id' => $destination->id,
                    'name' => $destination->destinationContentEsp->name
                ]);
            }

            $tours = $destination->tours->toArray();

            //dump($tours);

            $pos = array_search($request->id, array_column($tours, 'id'));
            //dump($pos);
            if ($pos !== false) {
                array_push($tours_checked, [
                    'check' => $destination->id,
                    'order' => $tours[$pos]['pivot']['order'] ?? 0
                ]);
            }
        }

        return response()->json([
            'status' => 'OK',
            'destinations' => $destinations_back,
            'checked' => $tours_checked
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

        $tour = tour::find($request->idTour);


        //$tour->destinations()->sync($request->destinations);


        $tour->destinations()->detach();
        foreach ($request->destinations as $des) {


            $tour->destinations()->attach([$des['check'] => ['order' => $des['order']]]);
        }

        return response()->json([
            'status' => 'OK'
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



    public function getDestinationsAll(Request $request)
    {


        //$destinations = destination::with('destinationContentEsp','destinationContentEng')->get();


        $destinations = destinationContent::select("destination_id as id", "destination_contents.show_home", "destination_contents.name", "destination_contents.url", "languages.name as idioma", "destination_contents.img", "destination_contents.order")
            ->join('destinations', 'destinations.id', '=', 'destination_contents.destination_id')
            ->join('languages', 'languages.id', '=', 'destination_contents.language_id')
            ->where('destinations.active', 1)
            ->where('languages.id', $request->language)
            ->orderBy('destination_contents.order', 'ASC')
            ->get();




        foreach ($destinations as $d) {
            $has_tours = false;
            $tours = destination::find($d['id'])->tours;

            // dump($tours);
            if (count($tours) > 0) {
                $has_tours = true;
            }
            $d['has_tours'] = $has_tours;
        }



        return response()->json(compact('destinations'));
    }


    public function getDestinationToFront(Request $request)
    {
        $destination = destinationContent::where('url', $request->url)->where('language_id', $request->idioma)->first();
        return  response()->json(compact('destination'));
    }


    public function getTourByDestination(Request $request)
    {

        $res = tourContent::join('tours', 'tour_contents.tour_id', '=', 'tours.id')
            ->join('price_tours', 'tour_contents.tour_id', '=', 'price_tours.tour_contents_id')
            ->join('destination_tour', 'tour_contents.tour_id', '=', 'destination_tour.tour_id')
            ->where('destination_tour.destination_id', $request->idCategory)
            ->where('tours.active', 1)
            ->where('tours.public', 1)

            ->where('language_id', $request->lenguage)
            ->orderBy('tour_contents.name', 'ASC')
            // ->orderBy(DB::raw('-destination_tour.order'), 'DESC')

            ->get();
        /*
        $res = tourContent::join('tours', 'tour_contents.tour_id', '=', 'tours.id')
    ->join('price_tours', 'tour_contents.tour_id', '=', 'price_tours.tour_contents_id')
    ->join('destination_tour', 'tour_contents.tour_id', '=', 'destination_tour.tour_id')
    ->where('destination_tour.destination_id', $request->idCategory)
    ->where('tours.active', 1)
    ->where('tours.public', 1)
    ->where('language_id', $request->lenguage)
    ->when($request->url === 'tours-chichenitza', function ($query) {
        $query->orderBy('price_tours.price', 'ASC'); // Ordena por precio si la URL es tours-chichenitza
    })
    ->orderBy(DB::raw('-destination_tour.order'), 'DESC') // Ordenamiento general
    ->get();
        */



        //dd($res);
        foreach ($res as $r) {
            if ($r->is_private) {
                /// rates to private tours

                $rates = DB::table('private_rates')->where('tour_id', $r->tour_id)->get();

                //dd($rates);

                /*
                    $r->fake_adult_mxn = $this->usdToMxn($r->price_fake_adult);
                    $r->real_adult_mxn = $this->usdToMxn($r->price_real_adult);

                    $r->price_fake_child_mxn = $this->usdToMxn($r->price_fake_child);
                    $r->price_real_child_mxn =  $this->usdToMxn($r->price_real_child);
                    $r->discount = ($r->price_fake_adult>0 && $r->price_real_adult) ? (($r->price_fake_adult - $r->price_real_adult) * 100) / $r->price_fake_adult : 0;


                    */
                //dd($tour->categories);

                $ratesToShow = [];

                foreach ($rates as $rate) {
                    $rate_from_fake_mxn = $this->usdToMxn($rate->rate_from_fake);
                    $rate_from_real_mxn = $this->usdToMxn($rate->rate_from_real);

                    $rate_fake_price_mxn = $this->usdToMxn($rate->fake_price);
                    $rate_real_price_mxn = $this->usdToMxn($rate->real_price);

                    array_push($ratesToShow, [
                        'rate_from_fake' => $rate->rate_from_fake,
                        'rate_from_fake_mxn' => $rate_from_fake_mxn,
                        'rate_from_real' => $rate->rate_from_real,
                        'rate_from_real_mxn' => $rate_from_real_mxn,
                        'fake_price' => $rate->fake_price,
                        'fake_price_mxn' => $rate_fake_price_mxn,
                        'real_price' => $rate->real_price,
                        'real_price_mxn' => $rate_real_price_mxn,
                        'pax' => $rate->pax_range_id

                    ]);
                    $r->discount = ($rate_from_fake_mxn > 0 && $rate_from_real_mxn) ? (($rate_from_fake_mxn - $rate_from_real_mxn) * 100) / $rate_from_fake_mxn : 0;
                }

                $r->rates = $ratesToShow;


                $tour = tour::with('categories')->find($r->tour_id);
                $categories = [];
                $is_private = false;
                foreach ($tour->categories as $cat) {
                    //dump($cat->id);
                    if ($cat->id == $request->idCategory) {
                        $contents = $cat->category_contents;
                        //dump($contents);
                        foreach ($contents as $content) {
                            //dump($content['language_id']);
                            if ($content->name === 'Tours privados' || $content->name === 'Private tours') {
                                $is_private = true;
                            }
                            if ($content->language_id == $request->lenguage) {
                                array_push($categories, $content);
                            }
                        }
                    }
                }
                $r->category = (count($categories) > 0) ? $categories[0] : null;
            } else {
                $r->fake_adult_mxn = $this->usdToMxn($r->price_fake_adult);
                $r->real_adult_mxn = $this->usdToMxn($r->price_real_adult);

                $r->price_fake_child_mxn = $this->usdToMxn($r->price_fake_child);
                $r->price_real_child_mxn =  $this->usdToMxn($r->price_real_child);
                $r->discount = ($r->price_fake_adult > 0 && $r->price_real_adult) ? (($r->price_fake_adult - $r->price_real_adult) * 100) / $r->price_fake_adult : 0;
                $tour = tour::with('categories')->find($r->tour_id);

                //dd($tour->categories);
                $categories = [];
                $is_private = false;
                foreach ($tour->categories as $cat) {
                    //dump($cat->id);
                    if ($cat->id == $request->idCategory) {
                        $contents = $cat->category_contents;
                        //dump($contents);
                        foreach ($contents as $content) {
                            //dump($content['language_id']);
                            if ($content->name === 'Tours privados' || $content->name === 'Private tours') {
                                $is_private = true;
                            }
                            if ($content->language_id == $request->lenguage) {
                                array_push($categories, $content);
                            }
                        }
                    }
                }
                $r->category = (count($categories) > 0) ? $categories[0] : null;
            }
        }


        return response()->json([
            "data" => $res,

        ], 200);
    }
}
