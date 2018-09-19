<?php

namespace App\Auth\Service;

use App\Casper\Model\User;
use App\Casper\Repository\SocialAccountRepository;
use App\Casper\Repository\UserRepository;
use App\Contracts\Auth\SocialUserResolver;
use Laravel\Socialite\Contracts\User as ProviderUser;
use App\Auth\Model\SocialAccount;
use App\Casper\Manager\UserManager;

abstract class AbstractSocialService implements SocialUserResolver
{
    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @var SocialAccountRepository
     */
    protected $accounts;

    /**
     * @var NicknameGenerator
     */
    protected $generator;

    /**
     * @var UserManager
     */
    protected $manager;

    public function __construct(
        UserRepository $repository,
        SocialAccountRepository $accounts,
        NicknameGenerator $generator,
        UserManager $manager
    ) {
        $this->users = $repository;
        $this->accounts = $accounts;
        $this->generator = $generator;
        $this->manager = $manager;
    }

    /**
     * @param ProviderUser $providerUser
     * @return User
     */
    public function resolveUser(ProviderUser $providerUser)
    {
        /** @var $account SocialAccount */
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
        return $this->accounts->findBySocialUserAndProvider($providerUser, $this->getProviderName());
    }

    /**
     * Creates new social account and user instance when necessary
     *
     * @param ProviderUser $providerUser
     * @return SocialAccount
     */
    protected function createNewAccount(ProviderUser $providerUser)
    {
        $account = new SocialAccount([
            'provider_user_id' => $providerUser->getId(),
            'provider' => $this->getProviderName()
        ]);

        $user = $this->users->findBySocialUser($providerUser);

        if (!$user) {
            $user = $this->manager->create([
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

    /**
     * The provider name of service
     *
     * @return string
     */
    abstract protected function getProviderName();
}
