<?php

namespace App\Http\Controllers\Auth;

use App\Auth\Service\SocialFacebookService;
use App\Casper\Model\User;
use Socialite;
use App\Http\Controllers\Controller;

class SocialFacebookController extends Controller
{
    /**
     * @var \Laravel\Socialite\Contracts\Factory
     */
    protected $socialite;

    public function __construct(\Laravel\Socialite\Contracts\Factory $socialite)
    {
        $this->socialite = $socialite;
    }

    /**
     * Create a redirect method to facebook api.
     *
     * @return
     */
    public function redirect()
    {
        return $this->getFacebookProvider()
            ->redirectUrl(route('auth.fb.callback'))
            ->redirect();
    }

    /**
     * Return a callback method from facebook api.
     *
     * @param SocialFacebookService $service
     *
     * @return callback URL from facebook
     */
    public function callback(SocialFacebookService $service)
    {
        try {
            $facebookUser = $this->getFacebookProvider()->user();
            /** @var $user User */
            $user = $service->createOrGetUser($facebookUser);
            auth()->login($user);

            return redirect()->to('/');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return redirect()->to('/')->withErrors(
                __('An error occurred during authorization, please try again.')
            );
        }
    }

    /**
     * @return \Laravel\Socialite\Two\FacebookProvider
     */
    protected function getFacebookProvider()
    {
        return $this->socialite->driver('facebook');
    }
}
