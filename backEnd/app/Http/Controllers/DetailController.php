<?php

namespace App\Http\Controllers;

use App\category;
use App\categoryContent;
use App\destinationContent;
use App\Gallery;
use App\SuggestionTour;
use App\tourContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DetailController extends Controller
{
    //

    public function pageType(Request $request){



        $vRules = [
            'url'=>'required',
            'language'=>'required'
        ];
        $validator =Validator::make($request->all(), $vRules);

        try {
            if ($validator->fails()) {
                $this->throwValidationException($request, $validator);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $validator->errors()], 400);
        }

        $val = '';

        if($request->url=='all-tours' || $request->url=='todos-tours'){

            $val='category';
            //$returnItem = $category;
            return response()->json([
                'status'=>'ok',
                'val'=>$val,
                //'data'=>$returnItem
            ]);

        }

        DB::enableQueryLog();
        $category = categoryContent::join('categories', 'category_contents.category_id', '=', 'categories.id')
            ->where('categories.active', 1)
            ->where('category_contents.language_id', $request->language)
            ->where('category_contents.url', 'like', '' . $request->url . '')
            ->get();

        $tour = tourContent::select(
                "tour_contents.*",
                "tours.*",
                "price_tours.*",
                "languages.name as idioma"
            )
            ->join('tours', 'tour_contents.tour_id', '=', 'tours.id')
            ->join('languages', 'languages.id', '=', 'tour_contents.language_id')
            ->join('price_tours', 'tour_contents.tour_id', '=', 'price_tours.tour_contents_id')
            ->where('tours.active', 1)
            ->where('tour_contents.url', 'like', '%' . $request->url . '%')
            ->where('languages.id', $request->language)
            ->get();


        $destination = destinationContent::select("destination_id as id",
                        "destination_contents.name", "destination_contents.url", "languages.name as idioma", "destination_contents.img", "destination_contents.meta_title","destination_contents.meta_description","meta_keywords")
        ->join('destinations', 'destinations.id', '=', 'destination_contents.destination_id')
        ->join('languages', 'languages.id', '=', 'destination_contents.language_id')
        ->where('destinations.active', 1)
        ->where('languages.id', $request->language)
        ->where('destination_contents.url','like','%'.$request->url.'%')
        ->orderBy('destination_contents.name', 'ASC')
        ->get();

        //dump('category ',$category);
        //dump('tour',$tour);
        //dump('destination ',$destination);

        //dump(DB::getQueryLog());
        if(!empty($category) && count($category)>0){
            $val='category';
            $returnItem = $category;
        }
        else if(!empty($tour) && count($tour)>0){
            $val='tour';
            //$returnItem = $tour;
            foreach($tour as $r){
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
                ->where('suggestion_tours.tours_id', $tour[0]->tour_id)
                ->where('suggestions.active', 1)
                ->orderBy('suggestions.name_esp', 'ASC')
                ->get();

            $gallery = Gallery::select("*")->where('tour_id', $tour[0]->tour_id)->get();

            $returnItem = [
                'tour'=>$tour,
                "suggestion" => $existentes,
                //"price" => $price,
                "gallery" => ($gallery->count() > 0) ? $gallery : []
            ];
        }
        elseif(!empty($destination) && count($destination)>0){
            /// is destination
            $val = 'destination';
            $returnItem= $destination;

        }

        //dd('finish');

        return response()->json([
            'status'=>'ok',
            'val'=>$val,
            'data'=>$returnItem
        ]);




    }
}
