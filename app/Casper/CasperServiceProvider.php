<?php

namespace App\Casper;

use App\Casper\Manager\EventManager;
use App\Casper\Manager\UserManager;
use App\Casper\Repository\EventInvitationsRepository;
use App\Casper\Repository\EventsRepository;
use App\Casper\Repository\GuestRepository;
use App\Casper\Repository\UserRepository;
use Illuminate\Support\ServiceProvider;

class CasperServiceProvider extends ServiceProvider
{
    /**
     * @inheritdoc
     */
    protected $defer = true;

    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->registerRepositories();

        $this->registerManagers();
    }

    protected function registerRepositories()
    {
        $this->app->singleton(EventsRepository::class);
        $this->app->singleton(GuestRepository::class);
        $this->app->singleton(EventInvitationsRepository::class);
        $this->app->singleton(UserRepository::class);
    }

    protected function registerManagers()
    {
        $this->app->singleton(EventManager::class);
        $this->app->singleton(UserManager::class);
        $this->app->singleton(EventInvitationManager::class);
    }

    /**
     * @inheritdoc
     */
    public function provides()
    {
        return [
            EventsRepository::class,
            GuestRepository::class,
            EventInvitationsRepository::class,
            UserRepository::class,
            EventManager::class,
            UserManager::class,
            EventInvitationManager::class,
        ];
    }
}
