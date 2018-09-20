<?php

namespace Tests\Unit\Http\Controllers;

use App\Auth\Exceptions\FailedAuthorizationException;
use App\Casper\Model\User;
use App\Http\Controllers\Auth\SocialFacebookController;
use App\Http\Controllers\Auth\SocialGoogleController;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * Class SocialControllerTest
 * @package Tests\Unit\Http\Controllers
 *
 * @group social-auth
 */
class SocialControllerTest extends TestCase
{
    protected $controllers = [
        SocialFacebookController::class,
        SocialGoogleController::class,
    ];

    /**
     * @test
     */
    public function it_handles_valid_oauth_callback()
    {
        $user = factory(User::class)->create();
        $socialUser = $this->createMock(\Laravel\Socialite\Contracts\User::class);
        $provider = $this->createMock(\Laravel\Socialite\Contracts\Provider::class);
        $provider->method('user')->willReturn($socialUser);

        $socialite = $this->createMock(\Laravel\Socialite\Contracts\Factory::class);
        $socialite->method('driver')->willReturn($provider);

        $userResolver = $this->createMock(\App\Contracts\Auth\SocialUserResolver::class);
        $userResolver->method('resolveUser')->willReturn($user);

        foreach ($this->controllers as $controllerClass) {
            $controller = new $controllerClass($socialite, $userResolver);
            $response = $controller->callback();
            $this->assertTrue($response instanceof RedirectResponse);
            $this->assertAuthenticatedAs($user);
        }
    }

    /**
     * @test
     * @expectedException \App\Auth\Exceptions\FailedAuthorizationException
     */
    public function it_throws_exception_for_invalid_authorization()
    {
        $provider = $this->createMock(\Laravel\Socialite\Contracts\Provider::class);
        $provider->method('user')
            ->willThrowException(
                new ClientException(
                    "Test exception message",
                    $this->createMock(\Psr\Http\Message\RequestInterface::class)
                )
            );

        $socialite = $this->createMock(\Laravel\Socialite\Contracts\Factory::class);
        $socialite->method('driver')->willReturn($provider);

        $userResolver = $this->createMock(\App\Contracts\Auth\SocialUserResolver::class);
        $userResolver->method('resolveUser')->willReturn(null);

        foreach ($this->controllers as $controllerClass) {
            $controller = new $controllerClass($socialite, $userResolver);
            $controller->callback();
        }
    }
}
