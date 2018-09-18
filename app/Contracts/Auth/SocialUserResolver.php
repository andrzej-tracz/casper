<?php

namespace App\Contracts\Auth;

use Laravel\Socialite\Contracts\User;

interface SocialUserResolver
{
    /**
     * Resolves user instance form given social user
     *
     * @param \Laravel\Socialite\Contracts\User $user
     * @return \App\Casper\Model\User
     */
    public function resolveUser(User $user);
}
