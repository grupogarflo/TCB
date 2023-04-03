<?php

namespace App\Http\Controllers;

use App\exchangeRate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private function tipoCambio()
    {
        $fecha = date("Y-m-d");
        $res = exchangeRate::select(
            "*"
        )
            ->where('start_date', '<=', $fecha)
            ->where('end_date', '>=', $fecha)
            ->orWhereNull('end_date')
            ->get();
        //->toSql();
        if (count($res) > 0) {
            return $res[0]->rate;
        } else {
            return 0;
        }
    }

    public function usdToMxn($amount){
        return $amount * $this->tipoCambio();
    }
}
