<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class destinationContent extends Model
{
    //


    public function destination(){
        return $this->belongsTo(destination::class);
    }
}
