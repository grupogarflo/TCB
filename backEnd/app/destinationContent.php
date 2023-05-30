<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class destinationContent extends Model
{
    //

    protected $appends=['full_photo_path'];

    public function getFullPhotoPathAttribute(){
        return env('APP_URL').'/storage'.$this->img;
    }

    public function destination(){
        return $this->belongsTo(destination::class);
    }
}
