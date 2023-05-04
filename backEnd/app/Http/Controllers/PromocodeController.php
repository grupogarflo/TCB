<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\tourContent;
use App\PromocodeTour;
use App\Promocode;

class PromocodeController extends Controller
{
    //

    function getAllPromoCode(Request $request)
    {
        $res = Promocode::select(
            "*"
        )
            ->where('active', 1)
            ->orderBy('code', 'ASC')
            ->get();
        //->toSql();

        return json_encode($res);
    }

    // recupera todos los tours y si aplica para el promocode
    function getAllToursPromocode(Request $request)
    {

        $arr = [];
        $checado = [];

        $res = tourContent::select(
            "tour_contents.name",
            "tours.id"
        )
            ->join('tours', 'tour_contents.tour_id', '=', 'tours.id')
            ->join('languages', 'languages.id', '=', 'tour_contents.language_id')
            ->where('tours.active', 1)
            ->where('languages.id', 1)
            ->orderBy('tour_contents.name', 'ASC')
            ->get();
        //->toSql();

        $existentes = $aplica = PromocodeTour::select(
            "*"
        )
            ->where('promocodes_id', $request->id)
            ->get();

        $aux = json_decode($existentes, true);
        for ($a = 0; $a < sizeof($res); $a++) {
            $key = array_search($res[$a]["id"], array_column($aux, 'tours_id'));
            if ($key !== false) {
                //$checado[] = $a;
                $checado[] = $res[$a]->id;
            }
        }

        $arr[0]["tours"] = $res;
        $arr[0]["checado"] = $checado;
        return json_encode($arr);
    }

    function addPromoCode(Request $request)
    {
        $promo = new Promocode();
        $promo->code = $request->promocode;
        $promo->discount_adult = $request->adulto;
        $promo->discount_child = $request->nino;
        $promo->date_start_booking = $request->datesBooking[0];
        $promo->date_end_booking = $request->datesBooking[1];
        $promo->date_start_travel = $request->datesTravel[0];
        $promo->date_end_travel = $request->datesTravel[1];
        $promo->type_discount = $request->tipo;
        $promo->save();

        $ultimoID = $promo->id;

        if (count($request->tours) > 0) {

            foreach ($request->tours as $value) {
                $tour = new PromocodeTour();
                $tour->promocodes_id = $ultimoID;
                $tour->tours_id = $value;
                $tour->save();
            }
        }

        return response()->json(
            [
                'message' => 'success'
            ],
            200
        );
    }

    function editPromoCode(Request $request)
    {

        Promocode::where('id', $request->id)
            ->update([
                "code" => $request->promocode,
                "discount_adult" => $request->adulto,
                "discount_child" => $request->nino,
                "date_start_booking" => $request->datesBooking[0],
                "date_end_booking"    => $request->datesBooking[1],
                "date_start_travel" => $request->datesTravel[0],
                "date_end_travel"    => $request->datesTravel[1],
                "type_discount"    => $request->tipo
            ]);

        $closeDay = new PromocodeTour();
        $closeDay->where('promocodes_id', $request->id)->delete();
        if (count($request->tours) > 0) {
            foreach ($request->tours as $value) {
                $tour = new PromocodeTour();
                $tour->promocodes_id = $request->id;
                $tour->tours_id = $value;
                $tour->save();
            }
        }
    }

    //recupera la informacion del tour con sus bloqueos
    function getInfoPromo(Request $request)
    {

        $promo = Promocode::select("*")
            ->where('id', $request->id)
            ->get();


        return response()->json(
            [
                'message' => 'success',
                'promo' => $promo,
                //'tours' => $tours
            ],
            200
        );
    }

    //elimina los bloqueos
    function deletePromoCode(Request $request)
    {
        $closeDay = new PromocodeTour();
        $closeDay->where('promocodes_id', $request->id)->delete();

        $closeDate = new Promocode();
        $closeDate->where('id', $request->id)->delete();
    }
}
