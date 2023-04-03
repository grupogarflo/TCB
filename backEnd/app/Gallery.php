<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    //


    protected $appends=['full_photo_path'];

    public function getFullPhotoPathAttribute(){
        return env('APP_URL').'/storage'.$this->img;
    }
}
