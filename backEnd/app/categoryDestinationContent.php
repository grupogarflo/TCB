<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class categoryDestinationContent extends Model
{


    public function category()
    {
        return $this->belongsToMany(
            category::class,
            //"category_categorydestinationcontent_destination_language"
            "link_table"

        );
    }
    public function destination()
    {
        return $this->belongsToMany(
            destination::class,
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

    public function espIdiomaSolo()
    {
        return $this->language()->wherePivot('id', 1);
    }

    /*
    public function categoryLink()
    {
        return $this->BelongsToMany(
            category::class,
            "category_categorydestinationcontent_destination_language",
            "destination_id"

        )->withTimestamps();
    }
    public function destinationLink()
    {
        return $this->BelongsToMany(
            destination::class,
            "category_categorydestinationcontent_destination_language",
            "destination_id"
        )->withTimestamps();
    }
    public function languageLink()
    {
        return $this->BelongsToMany(
            language::class,
            "category_categorydestinationcontent_destination_language",
            "destination_id"
        )->withTimestamps();
    }*/
}
