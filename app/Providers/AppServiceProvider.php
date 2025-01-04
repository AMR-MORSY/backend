<?php

namespace App\Providers;

use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Models\Users\PersonalAccessToken;
use App\services\NurHelpers\GetMonth\GetMonth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191);
    
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Sanctum::usePersonalAccessTokenModel(
            PersonalAccessToken::class
        );
    }
}
