<?php

namespace App\Providers;

use App\Auth\EmailOrNicknameUserProvider;
use App\Auth\Service\SocialFacebookService;
use App\Auth\Service\SocialGoogleService;
use App\Casper\Model\Event;
use App\Casper\Model\EventInvitation;
use App\Casper\Model\Guest;
use App\Contracts\Auth\SocialUserResolver;
use App\Http\Controllers\Auth\SocialFacebookController;
use App\Http\Controllers\Auth\SocialGoogleController;
use App\Policies\EventPolicy;
use App\Policies\GuestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Policies\EventInvitationPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Event::class => EventPolicy::class,
        EventInvitation::class => EventInvitationPolicy::class,
        Guest::class => GuestPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->registerAlternativeUserProvider();
        $this->configureSocialAuthorization();
    }

    /**
     * Registers custom user provider so it is possible to fetch users not only via email,
     * but also by nickname
     *
     */
    protected function registerAlternativeUserProvider()
    {
        $this->app['auth']->provider('eloquent_email_or_nickname_provider', function ($app, array $config) {
            return new EmailOrNicknameUserProvider($app['hash'], $config['model']);
        });
    }

    protected function configureSocialAuthorization()
    {
        $this->app
            ->when(SocialFacebookController::class)
            ->needs(SocialUserResolver::class)
            ->give(SocialFacebookService::class);

        $this->app
            ->when(SocialGoogleController::class)
            ->needs(SocialUserResolver::class)
            ->give(SocialGoogleService::class);
    }
}
