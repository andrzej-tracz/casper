<?php

namespace App\Casper\Repository;

use App\Auth\Model\SocialAccount;
use App\Eloquent\Repository\AbstractEloquentRepository;
use Laravel\Socialite\Contracts\User as SocialUser;

class SocialAccountRepository extends AbstractEloquentRepository
{
    /**
     * Returns the Model class of repository
     *
     * @return string
     */
    protected function getModelClass()
    {
        return SocialAccount::class;
    }

    /**
     * @param SocialUser $user
     * @param $providerName
     * @return mixed
     */
    public function findBySocialUserAndProvider(SocialUser $user, $providerName)
    {
        return $this->where('provider', $providerName)
            ->where('provider_user_id', $user->getId())
            ->first();
    }
}
