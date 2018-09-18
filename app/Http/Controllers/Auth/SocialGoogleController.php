<?php

namespace App\Http\Controllers\Auth;

use App\Auth\SocialServices;

class SocialGoogleController extends AbstractSocialController
{
    /**
     * @inheritdoc
     */
    protected function getProvider()
    {
        return $this->socialite->driver(SocialServices::GOOGLE);
    }
}
