<?php

namespace Tests\Unit\Casper\Repository;

use App\Casper\Model\User;
use App\Casper\Repository\UserRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Socialite\Two\User as SocialUser;
use Tests\TestCase;

/**
 * Class ResourcesTest
 * @package Tests\Unit\Casper\Resources
 *
 * @group resources
 */
class ResourcesTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var UserRepository
     */
    protected $reposiotory;

    protected function setUp()
    {
        parent::setUp();
        $this->reposiotory = app(UserRepository::class);
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->reposiotory = null;
    }

    /**
     * @test
     */
    public function it_searches_users()
    {
        $result = $this->reposiotory->searchUsers();
        $this->assertCount(10, $result);
    }

    /**
     * @test
     */
    public function it_resolves_by_social_user()
    {
        $user = factory(User::class)->create();
        $socialUser = new SocialUser();
        $socialUser->map([
            'email' => $user->email
        ]);

        $result = $this->reposiotory->findBySocialUser($socialUser);
        $this->assertEquals($result->getKey(), $user->getKey());
    }
}
