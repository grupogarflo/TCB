<?php

declare(strict_types=1);
namespace App;


use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\Client;


class PassportClient extends Client
{
    //

    public function skipsAuthorization()
    {
        // todo: add some checks, e.g. $this->name === 'spa-client'
        return $this->firstParty();
    }
}
