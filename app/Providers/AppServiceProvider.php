<?php

namespace App\Providers;

use App\Services\IPInfoService;
use App\Services\QrCodeService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            abstract: IPInfoService::class,
            concrete: fn($app) => new IPInfoService(request: $app->make('request'))
        );

        $this->app->singleton(
            abstract: QrCodeService::class,
            concrete: fn($app) => new QrCodeService()
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
