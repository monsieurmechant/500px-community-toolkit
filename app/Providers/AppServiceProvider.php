<?php

namespace App\Providers;

use Laravel\Dusk\DuskServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }
        $this->app->bind(\App\Services\Api\CreateApiDataFromCollection::class, function ($app) {
            return new \App\Services\Api\CreateApiDataFromCollection($app[\League\Fractal\Manager::class]);
        });
        $this->app->bind(\App\Services\Api\CreateApiDataFromModel::class, function ($app) {
            return new \App\Services\Api\CreateApiDataFromModel($app[\League\Fractal\Manager::class]);
        });
    }
}
