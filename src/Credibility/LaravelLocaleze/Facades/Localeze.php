<?php

namespace Credibility\LaravelLocaleze\Facades;

use Illuminate\Support\Facades\Facade;

class Localeze extends Facade{

    protected static function getFacadeAccessor()
    {
        return 'localeze';
    }

}