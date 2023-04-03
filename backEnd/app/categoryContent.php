<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class categoryContent extends Model
{
    //
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

    public function category(){
        return $this->belongsTo(category::class);
    }
}
