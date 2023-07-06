<?php

namespace App\Providers;

use App\src\Deviation\Deviation;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Deviation::class, static function () {
            $percent = Config::get('deviation.percent');
            return new Deviation($percent);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
