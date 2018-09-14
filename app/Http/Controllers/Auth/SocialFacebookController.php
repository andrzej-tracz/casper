<?php

namespace App\Http\Controllers\Auth;

use App\Auth\Service\SocialFacebookService;
use App\Casper\Model\User;
use Socialite;
use App\Http\Controllers\Controller;

class SocialFacebookController extends Controller
{
    /**
     * Create a redirect method to facebook api.
     *
     * @return
     */
    public function redirect()
    {
        return Socialite::driver('facebook')
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
        /** @var $user User */
        $user = $service->createOrGetUser(Socialite::driver('facebook')->user());
        auth()->login($user);

        return redirect()->to('/');
    }
}
