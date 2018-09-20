<?php

namespace Tests\Unit\Auth\Service;

use App\Auth\Service\NicknameGenerator;
use App\Casper\Model\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class SocialFacebookServiceTest
 * @package Tests\Unit\Auth\Service
 *
 * @group auth
 */
class NicknameGeneratorTest extends \Tests\TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_generates_proper_nicknames()
    {
        /** @var $service NicknameGenerator  */
        $service = app(NicknameGenerator::class);

        $this->assertDatabaseMissing('users', [
            'nickname' => 'this-not-exist'
        ]);

        $username = $service->generateUsernameFromEmail('this-not-exist@gmail.com');
        $this->assertEquals('this-not-exist', $username);

        factory(User::class)->create([
            'nickname' => 'john.foo'
        ]);

        $username = $service->generateUsernameFromEmail('john.foo@gmail.com');
        $this->assertEquals('john.foo_1', $username);

        factory(User::class)->create([
            'nickname' => 'john.foo_1'
        ]);

        $username = $service->generateUsernameFromEmail('john.foo@yahoo.com');
        $this->assertEquals('john.foo_2', $username);

        factory(User::class)->create([
            'nickname' => 'john.foo_2'
        ]);

        $username = $service->generateUsernameFromEmail('john.foo@yahoo.com');
        $this->assertEquals('john.foo_3', $username);
    }
}
