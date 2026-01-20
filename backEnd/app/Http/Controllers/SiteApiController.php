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
            // ->orderBy('price_tours.price_real_adult', 'ASC')
            ->orderBy(DB::raw('-tour_contents.order_home'), 'DESC')
            // ->orderByRaw('tour_contents.order_home DESC NULLS LAST')
            ->get();



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

                    array_push($ratesToShow, [
                        'rate_from_fake' => $rate->rate_from_fake,
                        'rate_from_fake_mxn' => $rate_from_fake_mxn,
                        'rate_from_real' => $rate->rate_from_real,
                        'rate_from_real_mxn' => $rate_from_real_mxn,
                        'fake_price' => $rate->fake_price,
                        'fake_price_mxn' => $rate_fake_price_mxn,
                        'real_price' => $rate->real_price,
                        'real_price_mxn' => $rate_real_price_mxn,
                        'pax' => $rate->pax_range_id,


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
            "data" => $res
        ], 200);
    }

    function getTourListSelect(Request $request)
    {
        /*$res = tourContent::select(
            "tour_contents.name",
            "tour_contents.url",
            "tours.id",
            "languages.name as idioma",
            "tour_contents.img"
        )
            ->join('tours', 'tour_contents.tour_id', '=', 'tours.id')
            ->join('languages', 'languages.id', '=', 'tour_contents.language_id')
            ->where('tours.active', 1)
            ->where('tours.public', 1)
            ->where('languages.id', $request->idioma)
            ->orderBy('tour_contents.name', 'ASC')
            ->get();*/

        DB::enableQueryLog();
        $res = tourContent::join('tours', 'tour_contents.tour_id', '=', 'tours.id')
            ->join('price_tours', 'tour_contents.tour_id', '=', 'price_tours.tour_contents_id')
            ->select(
                'tour_contents.id as id',
                'tour_contents.url as url',
                'tour_contents.name as name',
                'tour_contents.tour_id as tour_id',
                'tour_contents.is_private as is_private',
                'tour_contents.duration as duration',
                'tour_contents.img as img'
            )
            ->where('tours.active', 1)
            ->where('tours.public', 1)
            ->where('language_id', $request->idioma)
            // ->orderBy('tour_contents.name', 'ASC')
            ->orderBy('tour_contents.name', 'ASC')
            ->get();

        \Log::info('test ', [DB::getQueryLog()]);

        $arr = [];
        /*for ($a = 0; $a < sizeof($res); $a++) {
            $arr[$a]["name"] = $res[$a]->name;
            $arr[$a]["value"] = $res[$a]->url;
            $arr[$a]["id"] = $res[$a]->id;
            $arr[$a]["full_photo_path"] = env('APP_URL').'/storage'.$res[$a]->img;
        }*/
        foreach ($res as $r) {

            \Log::info('test ', [$r]);

            if ($r->is_private) {
                /// rates to private tours

                $rates = DB::table('private_rates')->where('tour_id', $r->tour_id)->get();



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

        \Log::info('procesed ', [$res]);
        //return json_encode($arr);
        return response()->json([
            "data" => $res
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
            // ->where('tour_contents.url', 'like', '%' . $request->id . '%')
            ->where('tour_contents.url',  $request->id)
            ->where('languages.id', $request->idioma)
            ->get();

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


        $existentes = SuggestionTour::select(
            "suggestion_tours.*",
            "suggestions.*"
        )
            ->join('suggestions', 'suggestion_tours.suggestions_id', '=', 'suggestions.id')
            ->where('suggestion_tours.tours_id', $res[0]->tour_id)
            ->where('suggestions.active', 1)
            ->orderBy('suggestions.name_esp', 'ASC')
            ->get();

        $gallery = Gallery::where('tour_id', $res[0]->tour_id)->orderByRaw('ISNULL(`order`), `order` ASC')->get();

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
            "data_mxn" => $this->usdToMxn($total),
            "priceAdult" => $priceSaleAdult,
            "priceChild" => $priceSaleChild,
            "priceAdult_mxn" => $this->usdToMxn($priceSaleAdult),
            "priceChild_mxn" => $this->usdToMxn($priceSaleChild)
        ], 200);
    }

    function getPromoCode(Request $request)
    {

        //proceso para los promocode que aplica a todos los tours sin importar fechas ni tours soliciatado por lorena para el 11 de nov al 3 de dic 2024

        $promocodeSpecial = [
            'BF2024OFFWM',
            'BF2024OFFHC',
            'BF2024OFFJL',
            'BF2024OFFBM',
            'BF2024OFFDG',
            'BF2024OFFHP'
        ];

        // Verificar si el promocode está en el arreglo y si la fecha actual está en el rango
        if (in_array($request->promocode, $promocodeSpecial)) {

            $starDateSpecial = '2024-11-08';
            $endDateSpecial = '2024-12-03';
            $fecha = Carbon::today()->format('Y-m-d');
            $startDate = Carbon::parse($starDateSpecial);
            $endDate = Carbon::parse($endDateSpecial);
            $discount = 5;

            if (Carbon::parse($fecha)->between($startDate, $endDate)) {

                if (!$request->is_private) {
                    $price = $this->getTotal($request);
                    $total = 0;
                    $auxPriceAdult = 0;
                    $auxPriceChild = 0;

                    //valido que tipo de descuento tiene

                    $calDescuento = ($discount * $price->original["priceAdult"]) / 100;
                    $auxPriceAdult = $price->original["priceAdult"] - $calDescuento;

                    if ($request->child > 0) {
                        $calDescuentoChild = ($discount * $price->original["priceChild"]) / 100;
                        $auxPriceChild = $price->original["priceChild"] - $calDescuentoChild;
                    }


                    $total = $auxPriceAdult * $request->adult;
                    if ($request->child > 0) {
                        $total += $auxPriceChild * $request->child;
                    }

                    $des = $price->original["data"] - $total;
                    return response()->json([
                        "data_usd" => $total,
                        "data_mxn" => $this->usdToMxn($total),
                        "last_usd" => $price->original["data"],
                        "last_mxn" => $this->usdToMxn($price->original["data"]),
                        "discount" => $des,
                        "discount_mxn" => $this->usdToMxn($des),
                        "promocode" => $request->promocode
                    ], 200);
                } else {

                    $price = $this->getTotal($request);
                    $total = 0;
                    $auxPriceAdult = 0;
                    $auxPriceChild = 0;


                    $rates = DB::table('private_rates')->where('tour_id', $request->id)->where('pax_range_id', $request->private_rate)->first();

                    // dd($rates);

                    //valido que tipo de descuento tiene

                    $calDescuento = ($discount * $rates->real_price) / 100;
                    $auxPriceAdult = $rates->real_price - $calDescuento;


                    $total = $auxPriceAdult;

                    $des = $rates->real_price - $total;
                    return response()->json([
                        "data_usd" => $total,
                        "data_mxn" => $this->usdToMxn($total),
                        "last_usd" =>  $rates->real_price,
                        "last_mxn" => $this->usdToMxn($price->original["data"]),
                        "discount" => $des,
                        "discount_mxn" => $this->usdToMxn($des),
                        "promocode" => $request->promocode
                    ], 200);
                }
            }
        } else {

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

                            if (!$request->is_private) {
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
                                    "data_mxn" => $this->usdToMxn($total),
                                    "last_usd" => $price->original["data"],
                                    "last_mxn" => $this->usdToMxn($price->original["data"]),
                                    "discount" => $des,
                                    "discount_mxn" => $this->usdToMxn($des),
                                    "promocode" => $request->promocode
                                ], 200);
                            } else {

                                $price = $this->getTotal($request);
                                $total = 0;
                                $auxPriceAdult = 0;
                                $auxPriceChild = 0;


                                $rates = DB::table('private_rates')->where('tour_id', $request->id)->where('pax_range_id', $request->private_rate)->first();

                                // dd($rates);

                                //valido que tipo de descuento tiene
                                if ($promoCode[0]->type_discount === "cantidad") {
                                    $auxPriceAdult = $rates->real_price - $promoCode[0]->discount_adult;
                                    if ($request->child > 0) {
                                        $auxPriceChild = $rates->real_price - $promoCode[0]->discount_child;
                                    }
                                } else {
                                    $calDescuento = ($promoCode[0]->discount_adult * $rates->real_price) / 100;
                                    $auxPriceAdult = $rates->real_price - $calDescuento;
                                }

                                $total = $auxPriceAdult;

                                $des = $rates->real_price - $total;
                                return response()->json([
                                    "data_usd" => $total,
                                    "data_mxn" => $this->usdToMxn($total),
                                    "last_usd" =>  $rates->real_price,
                                    "last_mxn" => $this->usdToMxn($price->original["data"]),
                                    "discount" => $des,
                                    "discount_mxn" => $this->usdToMxn($des),
                                    "promocode" => $request->promocode
                                ], 200);
                            }
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

        /*
        //codigo original
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

                            if (!$request->is_private) {
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
                                    "data_mxn" => $this->usdToMxn($total),
                                    "last_usd" => $price->original["data"],
                                    "last_mxn" => $this->usdToMxn($price->original["data"]),
                                    "discount" => $des,
                                    "discount_mxn" => $this->usdToMxn($des),
                                    "promocode" => $request->promocode
                                ], 200);
                            } else {

                                $price = $this->getTotal($request);
                                $total = 0;
                                $auxPriceAdult = 0;
                                $auxPriceChild = 0;


                                $rates = DB::table('private_rates')->where('tour_id', $request->id)->where('pax_range_id', $request->private_rate)->first();

                                // dd($rates);

                                //valido que tipo de descuento tiene
                                if ($promoCode[0]->type_discount === "cantidad") {
                                    $auxPriceAdult = $rates->real_price - $promoCode[0]->discount_adult;
                                    if ($request->child > 0) {
                                        $auxPriceChild = $rates->real_price - $promoCode[0]->discount_child;
                                    }
                                } else {
                                    $calDescuento = ($promoCode[0]->discount_adult * $rates->real_price) / 100;
                                    $auxPriceAdult = $rates->real_price - $calDescuento;
                                }

                                $total = $auxPriceAdult;

                                $des = $rates->real_price - $total;
                                return response()->json([
                                    "data_usd" => $total,
                                    "data_mxn" => $this->usdToMxn($total),
                                    "last_usd" =>  $rates->real_price,
                                    "last_mxn" => $this->usdToMxn($price->original["data"]),
                                    "discount" => $des,
                                    "discount_mxn" => $this->usdToMxn($des),
                                    "promocode" => $request->promocode
                                ], 200);
                            }
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
        */
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

        $res = BannerHome::where('active', 1)->where('languages_id', $request->idioma)->orderBy('alt', 'ASC')->get();
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

                $res[$a]['destinations_related'] = null;
                $related  = DB::table('link_table')->where('category_id', $res[$a]->category_id)->get();
                //dump($res[$a]->category_id);
                //dump($related);

                if (!empty($related) && count($related) > 0) {
                    $arr = [];
                    foreach ($related as $d) {

                        //dump($d);
                        $cont = destinationContent::where('destination_id', $d->destination_id)->get();

                        //dump($cont);

                        if (!empty($cont) && count($cont) > 0) {

                            foreach ($cont as $c) {
                                $name = '';
                                // dump($c->destination_id);
                                // dump($c->name);
                                // dump($c->language_id);
                                // dump('----');
                                if ($request->idioma == $c->language_id) {
                                    array_push($arr, [
                                        'id' => $c->destination_id,
                                        'url' => $c->url,
                                        'name_es' => ($c->language_id == 1) ? $c->name : null,
                                        'name_en' => ($c->language_id == 2) ? $c->name : null,

                                    ]);
                                }
                            }
                        }
                    }
                    $res[$a]->destinations_related = $arr;
                } else
                    $res[$a]['destinations_related'] = null;
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
        /*
        //DB::enableQuerylog();
        $res = tourContent::join('tours', 'tour_contents.tour_id', '=', 'tours.id')
            ->join('price_tours', 'tour_contents.tour_id', '=', 'price_tours.tour_contents_id')
            ->join('category_tours', 'tour_contents.tour_id', '=', 'category_tours.tour_id')
            ->where('category_tours.category_id', $request->idCategory)
            ->where('tours.active', 1)
            ->where('tours.public', 1)

            ->where('language_id', $request->lenguage)
            // ->orderBy('tour_contents.name', 'ASC')
            ->orderBy('price_tours.price_real_adult', 'ASC')

            ->get();
            */

        //dump(DB::getQuerylog());

        $order = [1, 2, 3, 5, 20, 7, 33, 14, 15, 16, 13, 6, 24, 12, 11, 4, 9, 10, 19, 18, 8, 29, 28, 30, 31];
        $orderString = implode(',', $order);

        $res = tourContent::join('tours', 'tour_contents.tour_id', '=', 'tours.id')
            ->join('price_tours', 'tour_contents.tour_id', '=', 'price_tours.tour_contents_id')
            ->join('category_tours', 'tour_contents.tour_id', '=', 'category_tours.tour_id')
            ->where('tours.active', 1)
            ->where('tours.public', 1)
            ->where('language_id', $request->lenguage)

            ->when($request->idCategory == 6, function ($query) use ($orderString) {
                // Condiciones específicas cuando idCategory es igual a 6
                $query
                    ->orderByRaw("FIELD(tours.id, $orderString)"); // Ordena por el campo específico
                // ->orderBy('price_tours.price_real_adult', 'ASC');   // Orden adicional
            }, function ($query) use ($request) {
                // Condiciones originales cuando idCategory es diferente de 6
                $query->where('category_tours.category_id', $request->idCategory)
                    ->orderBy('price_tours.price_real_adult', 'ASC');   // Orden original
            })
            ->get();




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
                    // dump($cat->id);
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

        // Filtra elementos duplicados en base a `tour_id` cuando sea el all tours
        if ($request->idCategory == 6) {
            $res = $res->unique('tour_id')->values();
        }


        return response()->json([
            "data" => $res,

        ], 200);
    }

    public function getAllTours(Request $request)
    {
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
            "data" => $res
        ], 200);
    }


    public function getPaxtRange(Request $request)
    {

        $data = DB::table('pax_ranges')->get();

        return response()->json(compact('data'));
    }
}
