<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class destination extends Model
{
    public function category()
    {
        return $this->belongsToMany(
            category::class,
            //"category_categorydestinationcontent_destination_language"
            "link_table"


        );
    }

    public function language()
    {
        return $this->belongsToMany(
            language::class,
            //"category_categorydestinationcontent_destination_language"
            "link_table"

        );
    }

    public function categoryDestinationContent()
    {
        return $this->belongsToMany(
            categoryDestinationContent::class,
            //"category_categorydestinationcontent_destination_language"
            "link_table"

        );
    }

    public function destinationContent(){
        return $this->hasMany(destinationContent::class);
    }

    public function destinationContentEsp(){
        return $this->hasOne(destinationContent::class)->where('language_id',1)->where('active',1);
    }
    public function destinationContentEng(){
        return $this->hasOne(destinationContent::class)->where('language_id',2)->where('active',1);
    }



    public function tours(){
        return $this->belongsToMany(tour::class, 'destination_tour','destination_id','tour_id')->withPivot('order')->withTimestamps();
    }


}
