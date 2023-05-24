<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tour extends Model
{
    //

    public function categories(){
        return $this->belongsToMany(category::class,'category_tours','tour_id', 'category_id');
    }


    public function destinations(){
        return $this->belongsToMany(destination::class, 'destination_tour','tour_id','destination_id')->withTimestamps();
    }
}
