<?php

namespace TeslaMN\SocialApi\Facades;

use Illuminate\Support\Facades\Facade;

class SocialApi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'socialapi';
    }
}
