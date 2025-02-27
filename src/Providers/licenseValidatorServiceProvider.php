<?php

namespace Tipusultan\LicenseValidator\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Tipusultan\LicenseValidator\Middleware\ValidateLicense;
use Illuminate\Contracts\Http\Kernel; 
class LicenseValidatorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
         // Bind middleware as a singleton (optional)
         $this->app->singleton(ValidateLicense::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Get the global Laravel HTTP Kernel
        $kernel = $this->app->make(Kernel::class);

        // Add middleware globally (it will run for every request)
        $kernel->pushMiddleware(ValidateLicense::class);
    }
}