<?php

namespace App\Providers;

use App\Models\WgClient;
use App\Observers\WgClientObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        WgClient::observe(WgClientObserver::class);
    }
}
