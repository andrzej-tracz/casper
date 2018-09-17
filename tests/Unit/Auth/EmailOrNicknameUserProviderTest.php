<?php

namespace Tests\Unit\Auth;

use Illuminate\Contracts\Hashing\Hasher;
use App\Auth\EmailOrNicknameUserProvider;

class EmailOrNicknameUserProviderTest extends \Tests\TestCase
{
    /**
     * @var EmailOrNicknameUserProvider
     */
    protected $provider;

    public function setUp()
    {
        parent::setUp();

        $hasher = $this->createMock(Hasher::class);
        $model = \App\Casper\Model\User::class;
        $this->provider = new \App\Auth\EmailOrNicknameUserProvider($hasher, $model);
    }

    public function tearDown()
    {
        $this->provider = null;
    }

    /**
     * @test
     */
    public function it_creates_instance_of_provider()
    {
        $this->assertTrue($this->provider instanceof EmailOrNicknameUserProvider);
    }

    /**
     * @test
     */
    public function it_returns_nothing_when_credentials_are_not_provided()
    {
        $credentials = [];
        $result = $this->provider->retrieveByCredentials($credentials);

        $this->assertEquals(null, $result);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function it_throws_an_exception_when_email_or_nickname_are_not_provided()
    {
        $credentials = [
            'name' => 'John'
        ];

        $this->provider->retrieveByCredentials($credentials);
    }
}
