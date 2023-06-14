<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\tour;
use App\tourContent;
use App\priceTour;
use App\Suggestion;
use App\SuggestionTour;
use App\exchangeRate;
use App\Promocode;
use App\PromocodeTour;
use App\PaymentClient;
use App\BannerHome;
use App\closedDate;
use App\closedDay;
use App\Gallery;
use App\category;
use App\categoryContent;
use App\categoryDestinationContent;
use App\categoryTour;
use App\destinationContent;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use App\Mail\ConfirmationSend;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

//use function GuzzleHttp\json_decode;

class SiteApiController extends Controller
{


    function getTourList(Request $request)
    {
        $res = tourContent::select(
            "tour_contents.img",
            "tour_contents.url",
            "tour_contents.name",
            "tour_contents.duration",
            "tour_contents.avaible",
            "tours.id",
            "price_tours.price_real_adult",
            "price_tours.price_fake_adult",
            "languages.name as idioma"
        )
            ->join('tours', 'tour_contents.tour_id', '=', 'tours.id')
            ->join('languages', 'languages.id', '=', 'tour_contents.language_id')
            ->join('price_tours', 'tour_contents.tour_id', '=', 'price_tours.tour_contents_id')
            ->where('tours.active', 1)
            ->where('tours.public', 1)
            ->where('languages.id', $request->idioma)
            ->orderBy('tour_contents.name', 'ASC')
            ->get();
        //->toSql();

        $arr = [];
        for ($a = 0; $a < sizeof($res); $a++) {
            $arr[$a]["img"] = $res[$a]->img;
            $arr[$a]["url"] = $res[$a]->url;
            $arr[$a]["name"] = $res[$a]->name;
            $arr[$a]["duration"] = $res[$a]->duration;
            $arr[$a]["avaible"] = $res[$a]->avaible;
            $arr[$a]["id"] = $res[$a]->id;
            //$arr[$a]["price_real_adult"] = ($request->idioma === 1) ? $res[$a]->price_real_adult * $this->tipoCambio() : $res[$a]->price_real_adult;
            if ($request->idioma === 1) {
                $adultoFake = $res[$a]->price_fake_adult * $this->tipoCambio();
                $adultoReal = $res[$a]->price_real_adult * $this->tipoCambio();
                if ($res[$a]->price_fake_adult > 0 && $res[$a]->price_real_adult > 0) {
                    $disconunt = (($adultoFake - $adultoReal) * 100) / $adultoFake;
                } else {
                    $disconunt = 0;
                }
            } else {
                $adultoFake = $res[$a]->price_fake_adult;
                $adultoReal = $res[$a]->price_real_adult;
                if ($res[$a]->price_fake_adult > 0 && $res[$a]->price_real_adult > 0) {
                    $disconunt = (($res[$a]->price_fake_adult - $res[$a]->price_real_adult) * 100) /  $res[$a]->price_fake_adult;
                } else {
                    $disconunt = 0;
                }
            }
            $arr[$a]["discount"] = round($disconunt);
            $arr[$a]["adultoFake"] = $adultoFake;
            $arr[$a]["adultoReal"] = $adultoReal;
        }

        return response()->json([
            "data" => $arr
        ], 200);
    }

    function getTourListHome(Request $request)
    {

        DB::enableQueryLog();
        $res = tourContent::join('tours', 'tour_contents.tour_id', '=', 'tours.id')
            ->join('price_tours', 'tour_contents.tour_id', '=', 'price_tours.tour_contents_id')
            ->where('tours.active', 1)
            ->where('tours.public', 1)
            ->where('tour_contents.top_home', 1)
            ->where('language_id', $request->idioma)
            // ->orderBy('tour_contents.name', 'ASC')
            ->orderBy('price_tours.price_real_adult', 'ASC')

            ->get();



        foreach($res as $r){
            $r->fake_adult_mxn = $this->usdToMxn($r->price_fake_adult);
            $r->real_adult_mxn = $this->usdToMxn($r->price_real_adult);

            $r->price_fake_child_mxn = $this->usdToMxn($r->price_fake_child);
            $r->price_real_child_mxn =  $this->usdToMxn($r->price_real_child);
            $r->discount = ($r->price_fake_adult>0 && $r->price_real_adult) ? (($r->price_fake_adult - $r->price_real_adult) * 100) / $r->price_fake_adult : 0;

            $tour = tour::with('categories')->find($r->tour_id);

            //dd($tour->categories);
            $categories = [];
            foreach($tour->categories as $cat){
                $contents = $cat->category_contents;
                foreach($contents as $content){
                    //dump($content['language_id']);
                    if($content->language_id==$request->idioma){
                        array_push($categories, $content);
                    }
                }
            }
            $r->category =(count($categories)>0) ? $categories[0] : null;



        }

        return response()->json([
            "data" => $res
        ], 200);
    }

    function getTourListSelect(Request $request)
    {
        $res = tourContent::select(
            "tour_contents.name",
            "tour_contents.url",
            "tours.id",
            "languages.name as idioma"
        )
            ->join('tours', 'tour_contents.tour_id', '=', 'tours.id')
            ->join('languages', 'languages.id', '=', 'tour_contents.language_id')
            ->where('tours.active', 1)
            ->where('tours.public', 1)
            ->where('languages.id', $request->idioma)
            ->orderBy('tour_contents.name', 'ASC')
            ->get();
        $arr = [];
        for ($a = 0; $a < sizeof($res); $a++) {
            $arr[$a]["name"] = $res[$a]->name;
            $arr[$a]["value"] = $res[$a]->url;
            $arr[$a]["id"] = $res[$a]->id;
        }

        //return json_encode($arr);
        return response()->json([
            "data" => $arr
        ], 200);
    }

    function getTourData(Request $request)
    {

        $res = tourContent::select(
                "tour_contents.*",
                "tours.*",
                "price_tours.*",
                "languages.name as idioma"
            )
            ->join('tours', 'tour_contents.tour_id', '=', 'tours.id')
            ->join('languages', 'languages.id', '=', 'tour_contents.language_id')
            ->join('price_tours', 'tour_contents.tour_id', '=', 'price_tours.tour_contents_id')
            ->where('tours.active', 1)
            ->where('tour_contents.url', 'like', '%' . $request->id . '%')
            ->where('languages.id', $request->idioma)
            ->get();

            foreach($res as $r){
                $r->price_fake_adult_mxn = $this->usdToMxn($r->price_fake_adult);
                $r->price_real_adult_mxn = $this->usdToMxn($r->price_real_adult);

                $r->price_fake_child_mxn = $this->usdToMxn($r->price_fake_child);
                $r->price_real_child_mxn =  $this->usdToMxn($r->price_real_child);
                $r->discount = ($r->price_fake_adult>0 && $r->price_real_adult) ? (($r->price_fake_adult - $r->price_real_adult) * 100) / $r->price_fake_adult : 0;
            }


        $existentes = SuggestionTour::select(
            "suggestion_tours.*",
            "suggestions.*"
        )
            ->join('suggestions', 'suggestion_tours.suggestions_id', '=', 'suggestions.id')
            ->where('suggestion_tours.tours_id', $res[0]->tour_id)
            ->where('suggestions.active', 1)
            ->orderBy('suggestions.name_esp', 'ASC')
            ->get();

        $gallery = Gallery::select("*")->where('tour_id', $res[0]->tour_id)->get();

        /*$price = [
            "pricePublicAdult" => ($request->idioma === 1) ? $res[0]->price_fake_adult * $this->tipoCambio() : $res[0]->price_fake_adult,
            "pricePublicChild" => ($request->idioma === 1) ? $res[0]->price_fake_child * $this->tipoCambio() : $res[0]->price_fake_child,
            "priceSaleAdult" => ($request->idioma === 1) ? $res[0]->price_real_adult * $this->tipoCambio() : $res[0]->price_real_adult,
            "priceSaleChild" => ($request->idioma === 1) ? $res[0]->price_real_child * $this->tipoCambio() : $res[0]->price_real_child
        ];*/

        return response()->json([
            "data" => $res,
            "suggestion" => $existentes,
            //"price" => $price,
            "gallery" => ($gallery->count() > 0) ? $gallery : []

        ], 200);
    }

    function getTotal(Request $request)
    {

        $res = tourContent::select(
            "tour_contents.*",
            "tours.*",
            "price_tours.*",
            "languages.name as idioma"
        )
            ->join('tours', 'tour_contents.tour_id', '=', 'tours.id')
            ->join('languages', 'languages.id', '=', 'tour_contents.language_id')
            ->join('price_tours', 'tour_contents.tour_id', '=', 'price_tours.tour_contents_id')
            ->where('tours.active', 1)
            ->where('tours.id', $request->id)
            ->where('languages.id', $request->idioma)
            ->get();

        $priceSaleAdult = $res[0]->price_real_adult;
        $priceSaleChild = $res[0]->price_real_child;

        //($request->idioma === 1) ? $res[0]->price_real_adult * $this->tipoCambio() : ;
        //$priceSaleChild = ($request->idioma === 1) ? $res[0]->price_real_child * $this->tipoCambio() : $res[0]->price_real_child;

        $total = 0;
        $total += $priceSaleAdult * $request->adult;
        if ($request->child > 0) {
            $total += $priceSaleChild * $request->child;
        }

        return response()->json([
            "data" => $total,
            "data_mxn"=>$this->usdToMxn($total),
            "priceAdult" => $priceSaleAdult,
            "priceChild" => $priceSaleChild,
            "priceAdult_mxn"=>$this->usdToMxn($priceSaleAdult),
            "priceChild_mxn"=> $this->usdToMxn($priceSaleChild)
        ], 200);
    }

    function getPromoCode(Request $request)
    {
        //valida el booking
        $fecha = date("Y-m-d");

        $promoCode = Promocode::select(
            "*"
        )
            ->where('code',  $request->promocode)
            ->where('active',  1)
            ->get();

        if (count($promoCode) > 0) {

            $booking = Promocode::select(
                "*"
            )
                ->where('date_start_booking', '<=', $fecha)
                ->where('date_end_booking', '>=', $fecha)
                ->where('id', $promoCode[0]->id)
                ->get();

            if (count($booking) > 0) {

                $travel = Promocode::select(
                    "*"
                )
                    ->where('date_start_travel', '<=', $request->date)
                    ->where('date_end_travel', '>=', $request->date)
                    ->where('id', $promoCode[0]->id)
                    ->get();
                if (count($travel) > 0) {
                    $tour = PromocodeTour::select(
                        "*"
                    )
                        ->where('promocodes_id', $promoCode[0]->id)
                        ->where('tours_id', $request->id)
                        ->get();
                    if (count($tour) > 0) {

                        //recupero el total
                        $price = $this->getTotal($request);
                        $total = 0;
                        $auxPriceAdult = 0;
                        $auxPriceChild = 0;

                        //valido que tipo de descuento tiene
                        if ($promoCode[0]->type_discount === "cantidad") {
                            $auxPriceAdult = $price->original["priceAdult"] - $promoCode[0]->discount_adult;
                            if ($request->child > 0) {
                                $auxPriceChild = $price->original["priceChild"] - $promoCode[0]->discount_child;
                            }
                        } else {
                            $calDescuento = ($promoCode[0]->discount_adult * $price->original["priceAdult"]) / 100;
                            $auxPriceAdult = $price->original["priceAdult"] - $calDescuento;

                            if ($request->child > 0) {
                                $calDescuentoChild = ($promoCode[0]->discount_child * $price->original["priceChild"]) / 100;
                                $auxPriceChild = $price->original["priceChild"] - $calDescuentoChild;
                            }
                        }

                        $total = $auxPriceAdult * $request->adult;
                        if ($request->child > 0) {
                            $total += $auxPriceChild * $request->child;
                        }

                        $des = $price->original["data"] - $total;
                        return response()->json([
                            "data_usd" => $total,
                            "data_mxn"=>$this->usdToMxn($total),
                            "last_usd" => $price->original["data"],
                            "last_mxn" => $this->usdToMxn($price->original["data"]),
                            "discount" => $des,
                            "discount_mxn"=>$this->usdToMxn($des),
                            "promocode" => $request->promocode
                        ], 200);
                    } else {
                        return response()->json([
                            "data" => "tour is not valid for this promocode"
                        ], 400);
                    }
                } else {
                    return response()->json([
                        "data" => "travel date is invalid for this promocode"
                    ], 400);
                }
            } else {
                return response()->json([
                    "data" => "booking date is invalid for this promocode"
                ], 400);
            }
        } else {
            return response()->json([
                "data" => "invalid promocode"
            ], 400);
        }
    }

    function getBannerHome(Request $request)
    {
        /*$res = BannerHome::select(
            "banner_homes.img",
            "banner_homes.alt",
            "banner_homes.url",
            "banner_homes.id as idImg",
        )
            ->join('languages', 'languages.id', '=', 'banner_homes.languages_id')
            ->where('banner_homes.active', 1)
            ->where('languages.id', $request->idioma)
            ->orderBy('banner_homes.alt', 'ASC')
            ->get();
        //->toSql();
        */

        $res = BannerHome::where('active',1)->where('languages_id',$request->idioma)->orderBy('alt','ASC')->get();
        if (count($res) > 0) {
            return response()->json([
                "data" => $res,

            ], 200);
        } else {
            return response()->json([
                "data" => '',
            ], 400);
        }
    }

    function getBlockDates(Request $request)
    {

        //recupera las fechas bloqueadas
        $res = closedDate::select(
            "*"
        )
            ->where('tours_id', $request->id)
            ->get();

        if (count($res) > 0) {
            $dates = [];
            for ($a = 0; $a < count($res); $a++) {
                // Declare an empty array
                //$array = array();

                // Declare two dates
                //$Date1 = $res[$a]["date_start"];
                //$Date2 = $res[$a]["date_end"];

                // Use strtotime function
                $Variable1 = strtotime($res[$a]["date_start"]);
                $Variable2 = strtotime($res[$a]["date_end"]);

                // Use for loop to store dates into array
                // 86400 sec = 24 hrs = 60*60*24 = 1 day
                for (
                    $currentDate = $Variable1;
                    $currentDate <= $Variable2;
                    $currentDate += (86400)
                ) {

                    $dates[] = date('Y-m-d', $currentDate);
                }
            }
            /*
            $dates = array($res[0]["date_start"]);
            while (end($dates) < $res[0]["date_end"]) {
                $dates[] = date('Y-m-d', strtotime(end($dates) . ' +1 day'));
            }
            */
        } else {
            $dates = [];
            // add blockdate  25th of december and 01 of January in all years
            $year = date("Y");
            for ($a = 1; $a <= 15; $a++) {
                $cadena = $year . "-12-25";
                array_push($dates, $cadena);

                $year = date("Y", strtotime(date("Y", strtotime($year)) . " + " . $a . " year"));

                $cadena2 = $year . "-01-01";
                array_push($dates, $cadena2);
            }
        }


        //recupera los dias de la semana bloqueados
        $days = closedDay::select(
            "*"
        )
            ->where('tours_id', $request->id)
            ->get();
        if (count($days) > 0) {
            $day = $days[0]["days"];
        } else {
            $day = [];
        }

        return response()->json([
            "date" => $dates,
            "days" => $day
        ], 200);
    }

    //genera las url estaticas de los tours
    function getListUrlGenerate(Request $request)
    {
        $res = tourContent::select(

            "tour_contents.url",
            "tour_contents.name",
            "tours.id",
            "languages.name as idioma"
        )
            ->join('tours', 'tour_contents.tour_id', '=', 'tours.id')
            ->join('languages', 'languages.id', '=', 'tour_contents.language_id')
            ->join('price_tours', 'tour_contents.tour_id', '=', 'price_tours.tour_contents_id')
            ->where('tours.active', 1)
            ->where('tours.public', 1)
            ->where('languages.id', $request->idioma)
            ->orderBy('tour_contents.name', 'ASC')
            ->get();
        //->toSql();

        $arr = [];
        $cont = 0;

        for ($a = 0; $a < sizeof($res); $a++) {
            $arr[$cont]["url"] = '/tour/' . $res[$a]->url;
            $cont++;
        }

        $res = categoryContent::select(
            "category_contents.id",
            "category_contents.name",
            "category_contents.url",
            "categories.id as category_id"
        )
            ->join('categories', 'category_contents.category_id', '=', 'categories.id')
            ->where('categories.active', 1)
            ->where('category_contents.language_id', $request->idioma)
            ->orderBy('category_contents.name', 'ASC')
            ->get();
        $extencion = "/category/";
        if ($request->idioma == 1) {
            $extencion = "/categoria/";
        }
        for (
            $a = 0;
            $a < sizeof($res);
            $a++
        ) {
            $arr[$cont]["url"] = $extencion . $res[$a]->url;
            $cont++;
        }
        /*
        return response()->json([
            "data" => $arr
        ], 200);
        */

        return $arr;
    }

    //get all categories
    function getAllCategories(Request $request)
    {
        $res = categoryContent::join('categories', 'category_contents.category_id', '=', 'categories.id')
            ->where('categories.active', 1)
            ->where('category_contents.language_id', $request->idioma)
            // ->orderBy('category_contents.name', 'ASC')
            ->orderBy('category_contents.orden', 'DESC')
            ->get();
        //->toSql();


        if (count($res) > 0) {
            //$arr = [];
            for ($a = 0; $a < sizeof($res); $a++) {
                /*
                $auxText = ($request->idioma == 2) ? 'category' : 'categoria';
                $arr[$a]["id"] = $res[$a]->id;
                $arr[$a]["name"] = $res[$a]->name;
                $arr[$a]["url"] =  $res[$a]->url;
                $arr[$a]["category_id"] = $res[$a]->category_id;
                */

                $res[$a]['destinations_related']=null;
                $related  = DB::table('link_table')->where('category_id', $res[$a]->category_id)->get();
                //dump($res[$a]->category_id);
                //dump($related);

                if(!empty($related) && count($related)>0){

                    foreach($related as $d){

                        //dump($d);
                        $cont = destinationContent::where('destination_id',$d->destination_id)->get();

                        //dump($cont);

                        if(!empty($cont) && count($cont)>0){
                            foreach($cont as $c){
                                $name = '';
                                $res[$a]->destinations_related=array([
                                    'id'=> $c->destination_id,
                                    'url'=>$c->url,
                                    'name_es'=>($c->language_id==1) ? $c->name : null,
                                    'name_en'=>($c->language_id==2) ? $c->name : null,

                                ]);
                            }
                        }

                    }
                }
                else
                    $res[$a]['destinations_related']=null;



            }

            return response()->json([
                "data" => $res,

            ], 200);
        } else {
            return response()->json([
                "data" => '',
            ], 400);
        }
    }

    //get specific category data
    function getCategory(Request $request)
    {
        $res = categoryContent::join('categories', 'category_contents.category_id', '=', 'categories.id')
            ->where('categories.active', 1)
            ->where('category_contents.language_id', $request->idioma)
            ->where('category_contents.url', 'like', '' . $request->url . '')
            ->get();
        //->toSql();
        //print_r($res);

        /*switch($res[0]->category_id){
            case 1: $res[0]["class_apply"] = 'mayan-ruins'; break;
            case 2: $res[0]["class_apply"] = 'aquatic'; break;
            case 4:  $res[0]["class_apply"] = 'luxury'; break;
            case 6:  $res[0]["class_apply"] = 'adventure'; break;
            default :  $res[0]["class_apply"] = ''; break;
        }*/

        if (count($res) > 0) {
            return response()->json([
                "data" => $res,

            ], 200);
        } else {
            return response()->json([
                "data" => '',
            ], 400);
        }
    }

    //get tour by specific category
    function getTourByCategory(Request $request)
    {
        //if idCategory is diferent of 9 get only tours of that id sending
        //else if idCategory is equal 9 then get all tours list
        /*if ($request->idCategory != 9) {
            $res = tourContent::join('tours', 'tour_contents.tour_id', '=', 'tours.id')
                ->join('languages', 'languages.id', '=', 'tour_contents.language_id')
                ->join('price_tours', 'tour_contents.tour_id', '=', 'price_tours.tour_contents_id')
                ->join('category_tours', 'tour_contents.tour_id', '=', 'category_tours.tour_id')
                ->where('category_tours.category_id', $request->idCategory)
                ->where('tours.active', 1)
                ->where('tours.public', 1)
                ->where('languages.id', $request->lenguage)
                // ->orderBy('tour_contents.name', 'ASC')
                ->orderBy('price_tours.price_real_adult', 'ASC')
                ->get();
        } else {
            $res = tourContent::join('tours', 'tour_contents.tour_id', '=', 'tours.id')
                ->join('languages', 'languages.id', '=', 'tour_contents.language_id')
                ->join('price_tours', 'tour_contents.tour_id', '=', 'price_tours.tour_contents_id')
                ->where('tours.active', 1)
                ->where('tours.public', 1)
                ->where('languages.id', $request->lenguage)
                // ->orderBy('tour_contents.name', 'ASC')
                ->orderBy('price_tours.price_real_adult', 'ASC')
                ->get();*/
            $res=tourContent::join('tours', 'tour_contents.tour_id', '=', 'tours.id')
            ->join('price_tours', 'tour_contents.tour_id', '=', 'price_tours.tour_contents_id')
            ->join('category_tours', 'tour_contents.tour_id', '=', 'category_tours.tour_id')
            ->where('category_tours.category_id', $request->idCategory)
            ->where('tours.active', 1)
            ->where('tours.public', 1)

            ->where('language_id', $request->lenguage)
            // ->orderBy('tour_contents.name', 'ASC')
            ->orderBy('price_tours.price_real_adult', 'ASC')

            ->get();
        //}

        //dump($res);

        /*if (count($res) > 0) {

            $arr = [];
            for ($a = 0; $a < sizeof($res); $a++) {
                $arr[$a]["img"] = $res[$a]->img;
                $arr[$a]["url"] = $res[$a]->url;
                $arr[$a]["name"] = $res[$a]->name;
                $arr[$a]["rank"] = $res[$a]->rank;
                $arr[$a]["duration"] = $res[$a]->duration;
                $arr[$a]["id"] = $res[$a]->id;

                //$arr[$a]["price_real_adult"] = ($request->idioma === 1) ? $res[$a]->price_real_adult * $this->tipoCambio() : $res[$a]->price_real_adult;
                if ($request->lenguage === 1) {
                    $adultoFake = $res[$a]->price_fake_adult * $this->tipoCambio();
                    $adultoReal = $res[$a]->price_real_adult * $this->tipoCambio();
                    if ($res[$a]->price_fake_adult > 0 && $res[$a]->price_real_adult > 0) {
                        $disconunt = (($adultoFake - $adultoReal) * 100) / $adultoFake;
                    } else {
                        $disconunt = 0;
                    }
                } else {
                    $adultoFake = $res[$a]->price_fake_adult;
                    $adultoReal = $res[$a]->price_real_adult;
                    if ($res[$a]->price_fake_adult > 0 && $res[$a]->price_real_adult > 0) {
                        $disconunt = (($res[$a]->price_fake_adult - $res[$a]->price_real_adult) * 100) /  $res[$a]->price_fake_adult;
                    } else {
                        $disconunt = 0;
                    }
                }
                $arr[$a]["discount"] = round($disconunt);
                $arr[$a]["adultoFake"] = $adultoFake;
                $arr[$a]["adultoReal"] = $adultoReal;
                $arr[$a]["full_photo_path"]=$res[$a]->full_photo_path;
            }*/
            foreach($res as $r){
                $r->fake_adult_mxn = $this->usdToMxn($r->price_fake_adult);
                $r->real_adult_mxn = $this->usdToMxn($r->price_real_adult);

                $r->price_fake_child_mxn = $this->usdToMxn($r->price_fake_child);
                $r->price_real_child_mxn =  $this->usdToMxn($r->price_real_child);
                $r->discount = ($r->price_fake_adult>0 && $r->price_real_adult) ? (($r->price_fake_adult - $r->price_real_adult) * 100) / $r->price_fake_adult : 0;
                $tour = tour::with('categories')->find($r->tour_id);

                //dd($tour->categories);
                $categories = [];
                foreach($tour->categories as $cat){
                    //dump($cat->id);
                    if($cat->id==$request->idCategory){
                        $contents = $cat->category_contents;
                        //dump($contents);
                        foreach($contents as $content){
                            //dump($content['language_id']);
                            if($content->language_id==$request->lenguage  ){
                                array_push($categories, $content);
                            }
                        }
                    }
                }
                $r->category =(count($categories)>0) ? $categories[0] :null;
            }


            return response()->json([
                "data" => $res,

            ], 200);
        /*} else {
            return response()->json([
                "data" => '',
            ], 400);
        }*/
    }

    public function getAllTours(Request $request){
        DB::enableQueryLog();
        $res = tourContent::join('tours', 'tour_contents.tour_id', '=', 'tours.id')
            ->join('price_tours', 'tour_contents.tour_id', '=', 'price_tours.tour_contents_id')
            ->where('tours.active', 1)
            ->where('tours.public', 1)
            ->where('language_id', $request->language)
            // ->orderBy('tour_contents.name', 'ASC')
            ->orderBy('price_tours.price_real_adult', 'ASC')

            ->get();

        //dump(DB::getQueryLog());



        foreach($res as $r){
            $r->fake_adult_mxn = $this->usdToMxn($r->price_fake_adult);
            $r->real_adult_mxn = $this->usdToMxn($r->price_real_adult);

            $r->price_fake_child_mxn = $this->usdToMxn($r->price_fake_child);
            $r->price_real_child_mxn =  $this->usdToMxn($r->price_real_child);
            $r->discount = ($r->price_fake_adult>0 && $r->price_real_adult) ? (($r->price_fake_adult - $r->price_real_adult) * 100) / $r->price_fake_adult : 0;

            $tour = tour::with('categories')->find($r->tour_id);

            //dd($tour->categories);
            $categories = [];
            foreach($tour->categories as $cat){
                //dump($cat);
                $contents = $cat->category_contents;
                foreach($contents as $content){
                    //dump($content['language_id']);
                    if($content->language_id==$request->language){
                        array_push($categories, $content);
                    }
                }
            }
            $r->category =(count($categories)>0) ? $categories[0] : null;



        }

        return response()->json([
            "data" => $res
        ], 200);


    }
}
