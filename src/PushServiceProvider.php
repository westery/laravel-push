<?php

namespace Westery\LaravelPush;
use Illuminate\Support\ServiceProvider;

/**
 * App Push ServiceProvider
 * Class PushServiceProvider
 * @package Westery\LaravelPush
 */
class PushServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/push.php' => config_path('push.php'),
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/push.php', 'push'
        );
        $this->app->singleton('Push', function ($app) {
            $app = new Push(config('push.default'));
            return $app;
        });
    }
}
