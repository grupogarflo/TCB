<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\tour;
use App\tourContent;
use App\closedDate;
use App\closedDay;

use Carbon\Carbon;

class CloseDatesController extends Controller
{
    //

    function getAllTourBlock(Request $request)
    {

        $res = tourContent::select(
            "tour_contents.name",
            "tour_contents.url",
            "languages.name as idioma",
            "tours.id",
            "tours.public"
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

            $banCloseDay = 0;
            $banCloseDate = 0;

            $closeDate = closedDate::select(
                "*"
            )
                ->where('tours_id', $res[$a]->id)
                ->get();
            if (count($closeDate) > 0) {
                $banCloseDate = 1;
            }

            $closeDay = closedDay::select(
                "*"
            )
                ->where('tours_id', $res[$a]->id)
                ->get();
            if (count($closeDay) > 0) {
                $banCloseDay = 1;
            }

            $arr[$a]["name"] = $res[$a]->name;
            $arr[$a]["url"] = $res[$a]->url;
            $arr[$a]["idioma"] = $res[$a]->idioma;
            $arr[$a]["id"] = $res[$a]->id;
            $arr[$a]["publicoShow"] = ($res[$a]->public === 1) ? "Si" : "No";
            $arr[$a]["showOp"] = ($banCloseDate === 1 or $banCloseDay === 1) ? "si" : "no";
        }

        return json_encode($arr);
    }

    function addBlock(Request $request)
    {
        // valida si tiene dias seleccionados para agregar el registro en la tabla
        if (count($request->days) > 0) {
            $aux = $request->days;
            if (count($request->days) === 8) {
                $index = array_search("todos", $aux);
                unset($aux[$index]);
                $aux = array_values($aux);
            }
            $closeDay = new closedDay();
            $closeDay->tours_id = $request->id;
            $closeDay->days = implode(",", $aux);
            $closeDay->save();
        }

        // valida si tiene un rango de fechas seleccionadas para agregar el registro en la tabla
        if (count($request->dates) > 0) {
            $closeDate = new closedDate();
            $closeDate->tours_id = $request->id;
            $closeDate->date_start = $request->dates[0];
            $closeDate->date_end = (count($request->dates) === 1) ? $request->dates[0] : $request->dates[1];;
            $closeDate->save();
            /*
            $startDate = new Carbon($request->dateStart);
            $endDate = new Carbon($request->dateEnd);

            $all_dates = array();
            while ($startDate->lte($endDate)) {
                $closeDate = new closedDate();
                $closeDate->tours_id = $request->id;
                $closeDate->date = $startDate->toDateString();
                $closeDate->save();
                $startDate->addDay();
            }
            */
        }

        return response()->json(
            [
                'message' => 'success'
            ],
            200
        );
    }

    function editBlock(Request $request)
    {
        $closeDay = new closedDay();
        $closeDay->where('tours_id', $request->id)->delete();

        // valida si tiene dias seleccionados para agregar el registro en la tabla
        if (count($request->days) > 0) {
            $aux = $request->days;
            if (count($request->days) === 8) {
                $index = array_search("todos", $aux);
                unset($aux[$index]);
                $aux = array_values($aux);
            }

            $closeDay->tours_id = $request->id;
            $closeDay->days = implode(",", $aux);
            $closeDay->save();
        }

        // valida si tiene un rango de fechas seleccionadas para agregar el registro en la tabla


        $closeDate = new closedDate();
        $closeDate->where('tours_id', $request->id)->delete();

        if (count($request->dates) > 0) {
            $closeDate = new closedDate();
            $closeDate->tours_id = $request->id;
            $closeDate->date_start = $request->dates[0];
            $closeDate->date_end = (count($request->dates) === 1) ? $request->dates[0] : $request->dates[1];
            $closeDate->save();

            /*
            $startDate = new Carbon($request->dateStart);
            $endDate = new Carbon($request->dateEnd);
            $all_dates = array();
            while ($startDate->lte($endDate)) {

                $closeDate->tours_id = $request->id;
                $closeDate->date = $startDate->toDateString();
                $closeDate->save();
                $startDate->addDay();
            }
            */
        }
    }
    //recupera la informacion del tour con sus bloqueos
    function getInfoBlock(Request $request)
    {
        $closeDays = closedDay::select("days")
            ->where('tours_id', $request->id)
            ->get();

        $closeDate = closedDate::select("date_start", "date_end")
            ->where('tours_id', $request->id)
            ->get();

        return response()->json(
            [
                'message' => 'success',
                'dates' => $closeDate,
                'days' => $closeDays
            ],
            200
        );
    }

    //elimina los bloqueos
    function deleteBlock(Request $request)
    {
        $closeDate = new closedDate();
        $closeDate->where('tours_id', $request->id)->delete();

        $closeDay = new closedDay();
        $closeDay->where('tours_id', $request->id)->delete();
    }
}
