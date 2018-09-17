<?php

namespace App\Auth\Service;

use App\Casper\Model\User;
use App\Casper\Repository\UserRepository;
use Laravel\Socialite\Contracts\User as ProviderUser;
use App\Auth\Model\SocialFacebookAccount;

class SocialFacebookService
{
    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @var NicknameGenerator
     */
    protected $generator;

    public function __construct(UserRepository $repository, NicknameGenerator $generator)
    {
        $this->users = $repository;
        $this->generator = $generator;
    }

    /**
     * @param ProviderUser $providerUser
     * @return User
     */
    public function createOrGetUser(ProviderUser $providerUser)
    {
        $account = $this->fetchAccount($providerUser) ?: $this->createNewAccount($providerUser);

        return $account->user;
    }

    /**
     * Fetches existing social account
     *
     * @param ProviderUser $providerUser
     * @return mixed
     */
    protected function fetchAccount(ProviderUser $providerUser)
    {
        return SocialFacebookAccount::whereProvider('facebook')
            ->whereProviderUserId($providerUser->getId())
            ->first();
    }

    /**
     * Creates new social account and user instance when necessary
     *
     * @param ProviderUser $providerUser
     * @return SocialFacebookAccount
     */
    protected function createNewAccount(ProviderUser $providerUser)
    {
        $account = new SocialFacebookAccount([
            'provider_user_id' => $providerUser->getId(),
            'provider' => 'facebook'
        ]);

        $user = $this->users->whereEmail($providerUser->getEmail())->first();

        if (!$user) {
            $user = $this->users->create([
                'email' => $providerUser->getEmail(),
                'nickname' => $this->generator->generateUsernameFromEmail(
                    $providerUser->getEmail()
                ),
                'password' => md5(rand(1, 10000)),
            ]);
        }

        $account->user()->associate($user);
        $account->save();

        return $account;
    }
}
