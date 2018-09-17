<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerIsActiveDirective();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    protected function registerIsActiveDirective()
    {
        Blade::directive('is_active', function ($name) {

            if (\Illuminate\Support\Str::startsWith($name, \Request::route()->getName())) {
                return "<?php echo 'active'; ?>";
            };

            return '';
        });
    }
}
