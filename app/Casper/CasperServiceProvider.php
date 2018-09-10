<?php

namespace App\Casper;

use App\Casper\Manager\EventManager;
use App\Casper\Manager\UserManager;
use App\Casper\Repository\EventsRepository;
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
    }

    protected function registerManagers()
    {
        $this->app->singleton(EventManager::class);
        $this->app->singleton(UserManager::class);
    }

    /**
     * @inheritdoc
     */
    public function provides()
    {
        return [
            EventsRepository::class,
            EventManager::class,
            UserManager::class,
        ];
    }
}
