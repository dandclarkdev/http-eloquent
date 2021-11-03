<?php

namespace LaravelHttpEloquent\Providers;

use Illuminate\Support\ServiceProvider;
use LaravelHttpEloquent\ConfigProviders\LaravelConfigProvider;
use LaravelHttpEloquent\HttpClients\LaravelHttpClient;
use LaravelHttpEloquent\ServiceFactory;
use LaravelHttpEloquent\Interfaces\ServiceFactory as ServiceFactoryInterface;

class LaravelHttpEloquentServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ServiceFactoryInterface::class, function () {
            return new ServiceFactory(
                new LaravelConfigProvider(),
                new LaravelHttpClient()
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/laravelhttpeloquent.php' => config_path('laravelhttpeloquent.php'),
        ]);
    }
}
