<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BannerHome;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class BannerHomeController extends Controller
{
    //recuepra todas las categorias
    function getAll()
    {



        /*
            $res = BannerHome::select(
                "banner_homes.*",
                "banner_homes.id as idImg",
                "languages.*",
                "languages.id as idioma"
            )
                ->join('languages', 'languages.id', '=', 'banner_homes.languages_id')
                ->where('banner_homes.active', 1)
                //->where('languages.id', 1)
                ->orderBy('banner_homes.alt', 'ASC')
                ->get();
            //->toSql();
        */

        $res = BannerHome::where('active',1)->orderBy('alt','ASC')->get();

        foreach($res as $item){
            $item->idioma= ($item->languages_id==1) ? 'Español' :'Ingles';
            $item->url= $item->url??'-';
        }

        $arr = [];
        /*for ($a = 0; $a < sizeof($res); $a++) {
            $arr[$a]["id"] = $res[$a]->idImg;
            $arr[$a]["img"] = asset('storage'.$res[$a]->img);
            $arr[$a]["alt"] = $res[$a]->alt;
            $arr[$a]["url"] = $res[$a]->url;
            $arr[$a]["idioma"] = ($res[$a]->idioma === 1) ? "Español" : "Ingles";
            $arr[$a]["idIdioma"] = $res[$a]->idioma;
        }*/

        return response()->json($res);
        //return json_encode($arr);
        //return $res;
    }

    function addBannerHome(Request $request)
    {

        try {


            if ($request->hasFile('image')) {
                $timeStamp= uniqid();
                $url ='/bannerhome/'.$timeStamp.'_'.$request->file('image')->getClientOriginalName();

                Storage::disk('public')->put($url, File::get($request->file('image')));




            }
            /*$request->file('image')->storeAs(
                    'public/bannerhome',

                );*/
            $add = new BannerHome();
            $add->img = $url;
            $add->alt = $request->alt;
            $add->url = $request->urlLink;
            $add->languages_id = $request->idioma;
            $add->save();
            return response()->json([
                'message' => 'success'
            ], 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json([
                'message' => 'error add image'
            ], 404);

        }
    }

    function editBannerHome(Request $request)
    {
        try {
            //valida si se modifico la imagen en la edicion
            if ($request->image != 'null') {
                /*$timeStamp= uniqid();
                $url =
                    $request->file('image')->storeAs(
                        'bannerhome',
                        $timeStamp.'_'.$request->file('image')->getClientOriginalName()
                    );*/
                if ($request->hasFile('image')) {
                    $timeStamp= uniqid();
                    $url ='/bannerhome/'.$timeStamp.'_'.$request->file('image')->getClientOriginalName();

                    Storage::disk('public')->put($url, File::get($request->file('image')));




                }
                BannerHome::where('id', $request->id)
                    ->update([
                        "img" => $url,
                        "alt" => $request->alt,
                        "url" => $request->urlLink,
                        "languages_id" => $request->idioma,
                    ]);
            } else {
                BannerHome::where('id', $request->id)
                    ->update([
                        "alt" => $request->alt,
                        "url" => $request->urlLink,
                        "languages_id" => $request->idioma,
                    ]);
            }

            return response()->json([
                'message' => 'success'
            ], 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json([
                'message' => 'error update data img'
            ], 404);
        }
    }

    function deleteBannerHome(Request $request)
    {

        try {
            $cat = BannerHome::find($request->id);
            $cat->active = 0;
            $cat->save();

            return response()->json([
                'message' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'banner not found or deleted'
            ], 404);
        }
    }
}
