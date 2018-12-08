<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Contracts\ShopRepository::class, \App\Repositories\ShopRepositoryEloquent::class);
        $this->app->bind(\App\Contracts\DefinitionRepository::class, \App\Repositories\DefinitionRepositoryEloquent::class);
        $this->app->bind(\App\Contracts\SettingRepository::class, \App\Repositories\SettingRepositoryEloquent::class);
        $this->app->bind(\App\Contracts\BillingRepository::class, \App\Repositories\BillingRepositoryEloquent::class);
        $this->app->bind(\App\Contracts\SocialMediaRepository::class, \App\Repositories\SocialMediaRepositoryEloquent::class);
        //:end-bindings:
    }
}
