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
}
