<?php

namespace Kejubayer\PathaoIntegration\Facades;

use Illuminate\Support\Facades\Facade;

class Pathao extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'pathao';
    }
}