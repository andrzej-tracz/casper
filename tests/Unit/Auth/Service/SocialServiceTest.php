<?php

namespace Tests\Unit\Auth\Service;

use App\Auth\Model\SocialAccount;
use App\Auth\Service\AbstractSocialService;
use App\Auth\Service\NicknameGenerator;
use App\Auth\Service\SocialFacebookService;
use App\Auth\Service\SocialGoogleService;
use App\Casper\Manager\UserManager;
use App\Casper\Repository\SocialAccountRepository;
use App\Casper\Repository\UserRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Socialite\Two\User;
use Faker;

/**
 * Class SocialFacebookServiceTest
 * @package Tests\Unit\Auth\Service
 *
 * @group auth
 */
class SocialServiceTest extends \Tests\TestCase
{
    use DatabaseTransactions;

    protected $service;

    protected $services = [
        'google' => SocialGoogleService::class,
        'facebook' => SocialFacebookService::class
    ];

    public function tearDown()
    {
        parent::tearDown();
        $this->service = null;
    }

    /**
     * @test
     */
    public function it_creates_new_social_account_and_user_if_not_exists()
    {
        foreach ($this->services as $name => $class) {
            $this->createsNewAccountIfNotExists($name, $class);
            \Mockery::close();
        }
    }

    /**
     * @test
     */
    public function it_fetches_social_account_if_already_exists()
    {
        foreach ($this->services as $name => $class) {
            $this->fetchesAccountIfExists($name, $class);
        }
    }

    protected function createsNewAccountIfNotExists($name, $class)
    {
        $faker = Faker\Factory::create();
        $accounts = \Mockery::mock(SocialAccountRepository::class);
        $socialUser = new User();
        $socialUser->map([
            'id' => uniqid(),
            'email' => $faker->safeEmail
        ]);

        $accounts->shouldReceive('findBySocialUserAndProvider')
            ->with($socialUser, $name)
            ->once()
            ->andReturn(null);

        $users = \Mockery::mock(UserRepository::class);
        $users->shouldReceive('findBySocialUser')
            ->with($socialUser)
            ->once()
            ->andReturn(null);

        $generator = app(NicknameGenerator::class);
        $manger = app(UserManager::class);

        /** @var $service AbstractSocialService */
        $service = new $class($users, $accounts, $generator, $manger);
        $resolved = $service->resolveUser($socialUser);

        $this->assertTrue($resolved instanceof \App\Casper\Model\User);
        $this->assertTrue($resolved->wasRecentlyCreated);
        $this->assertTrue($resolved->socialAccounts()->exists());
        $socialAccount = $resolved->socialAccounts()->first();

        $this->assertTrue($socialAccount instanceof SocialAccount);
        $this->assertEquals($socialAccount->provider_user_id, $socialUser->getId());
    }

    protected function fetchesAccountIfExists(string $name, $class)
    {
        $faker = Faker\Factory::create();
        $socialUser = new User();
        $socialUser->map([
            'id' => uniqid(),
            'email' => $faker->safeEmail
        ]);

        $user = factory(\App\Casper\Model\User::class)->create();
        $user->socialAccounts()->create([
            'provider_user_id' => $socialUser->getId(),
            'provider' => $name
        ]);

        $service = app($class);
        $resolved = $service->resolveUser($socialUser);

        $this->assertTrue($resolved instanceof \App\Casper\Model\User);
        $this->assertEquals($user->getKey(), $resolved->getKey());
        $this->assertEquals($user->email, $resolved->email);
    }
}
