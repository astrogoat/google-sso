<?php

namespace Astrogoat\GoogleSSO;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Astrogoat\GoogleSSO\GoogleSSO
 */
class GoogleSSOFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'google-sso';
    }
}
