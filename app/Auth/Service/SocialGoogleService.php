<?php

namespace App\Auth\Service;

use App\Auth\SocialServices;

class SocialGoogleService extends AbstractSocialService
{
    /**
     * The provider name of service
     *
     * @return string
     */
    protected function getProviderName()
    {
        return SocialServices::GOOGLE;
    }
}
