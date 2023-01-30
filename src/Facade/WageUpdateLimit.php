<?php

namespace myWagepay\Baas\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * Class WageOboard
 * @package myWagepay\Baas\Facade
 */
class WageUpdateLimit extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'wage_update_limit';
    }
}
