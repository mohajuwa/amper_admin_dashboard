<?php

namespace App\Providers;

use App\Models\SMTPModel;
use App\Rules\ValidateGoogleMapsUrlAndComments;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
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
        Paginator::useBootstrap();
        Validator::extend('validate_google_maps_url', ValidateGoogleMapsUrlAndComments::class . '@passes');

        
        // $websiteSettings = Setting::first();
        // View::share('appSetting', $websiteSettings);
    }
}
