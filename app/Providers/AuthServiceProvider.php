<?php

namespace App\Providers;

use App\Auth\EmailOrNicknameUserProvider;
use App\Casper\Model\Event;
use App\Policies\EventPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Event::class => EventPolicy::class
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
}
