<?php

namespace App\Http\Controllers\Auth;

use App\Auth\Exceptions\FailedAuthorizationException;
use App\Contracts\Auth\SocialUserResolver;
use App\Http\Controllers\Controller;

abstract class AbstractSocialController extends Controller
{
    /**
     * @var \Laravel\Socialite\Contracts\Factory
     */
    protected $socialite;

    /**
     * @var \App\Contracts\Auth\SocialUserResolver
     */
    protected $userResolver;

    public function __construct(
        \Laravel\Socialite\Contracts\Factory $socialite,
        SocialUserResolver $resolver
    ) {
        $this->socialite = $socialite;
        $this->userResolver = $resolver;
    }

    /**
     * Create a redirect to Facebook.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect()
    {
        return $this->getProvider()->redirect();
    }

    /**
     * Handles redirection from Social service and resolves authorized user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback()
    {
        try {
            $socialUser = $this->getProvider()->user();
            $user = $this->userResolver->resolveUser($socialUser);

            auth()->login($user);

            return redirect()->to('/');
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            throw new FailedAuthorizationException(
                'Error occurs during external authorization',
                $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * Returns provider instance for authorization process
     *
     * @return \Laravel\Socialite\Contracts\Provider|\Laravel\Socialite\Two\FacebookProvider
     */
    abstract protected function getProvider();
}
