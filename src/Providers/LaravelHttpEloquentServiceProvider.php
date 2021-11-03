<?php

namespace HttpEloquent\Providers;

use Illuminate\Support\ServiceProvider;
use HttpEloquent\ConfigProviders\LaravelConfigProvider;
use HttpEloquent\HttpClients\LaravelHttpClient;
use HttpEloquent\ServiceFactory;
use HttpEloquent\Interfaces\ServiceFactory as ServiceFactoryInterface;

class HttpEloquentServiceProvider extends ServiceProvider
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
