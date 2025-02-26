<?php

namespace Tipusultan\LicenseValidator\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Http\Kernel;
use Tipusultan\LicenseValidator\Middleware\ValidateLicense;

class LicenseValidatorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
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
