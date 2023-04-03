<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class tourContent extends Model
{
    //

    protected $appends=['full_photo_path'];

    public function getFullPhotoPathAttribute(){
        return env('APP_URL').'/storage'.$this->img;
    }



}
