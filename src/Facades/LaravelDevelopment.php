<?php

namespace iVirtual\LaravelDevelopment\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @mixin \iVirtual\LaravelDevelopment\LaravelDevelopment
 */
class LaravelDevelopment extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \iVirtual\LaravelDevelopment\LaravelDevelopment::class;
    }
}
