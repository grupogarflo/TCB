<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class category extends Model
{


    protected $appends=[
        'class_apply',
    ];


    public function getClassApplyAttribute(){
        $class='';
        switch($this->category_id){
            case 1: $class = 'mayan-ruins'; break;
            case 2: $class = 'aquatic'; break;
            case 4: $class = 'luxury'; break;
            case 6: $class = 'adventure'; break;
        }
        return $class;
    }




    public function destination()
    {
        return $this->belongsToMany(
            destination::class,
            ""


        );
    }
    public function language()
    {
        return $this->belongsToMany(
            language::class,
            ""

        );
    }
    public function categoryDestinationContent()
    {
        return $this->belongsToMany(
            categoryDestinationContent::class,
            ""

        );
    }

    public function tours(){
        return $this->belongsToMany(Tours::Class,'category_tour','category_id','tour_id');
    }


    public function category_contents(){
        return $this->hasMany(categoryContent::class);
    }
}
