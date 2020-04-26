<?php

namespace Jordywolk\Ts3api;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Jordywolk\Ts3api\Skeleton\SkeletonClass
 */
class Ts3apiFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ts3api';
    }
}
