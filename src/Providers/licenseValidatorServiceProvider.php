<?php

namespace Tipusultan\LicenseValidator\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Tipusultan\LicenseValidator\Middleware\ValidateLicense;

class LicenseValidatorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register middleware
        $this->app->singleton(ValidateLicense::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(Router $router): void
    {
        // Register middleware alias
        $router->aliasMiddleware('license.validate', ValidateLicense::class);
    }
}