<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BannerHome extends Model
{
    //

    protected $appends=['full_photo_path'];

    public function getFullPhotoPathAttribute(){
        return env('APP_URL').'/storage'.$this->img;
    }

}
