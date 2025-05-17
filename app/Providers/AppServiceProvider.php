<?php

namespace App\Providers;

use App\Http\Middleware\CustomThrottleRequests;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {  
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Router $router)
    {
        //
        $router->aliasMiddleware('custom.throttle', CustomThrottleRequests::class);
    }
}
