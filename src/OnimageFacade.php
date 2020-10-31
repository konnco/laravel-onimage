<?php

namespace Konnco\Onimage;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Konnco\Onimage\Onimage
 */
class OnimageFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-onimage';
    }
}
