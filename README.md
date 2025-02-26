# Laravel License Validator

A simple Laravel package for validating software licenses.

## Installation

Install the package via composer:

```bash
composer require tipusultan/licensevalidator
```

## Usage

### 1. Set up license key

Add your license key to your `.env` file:

```
PUSHER_VALIDATION_KEY=your-license-key
```



After installing the package, the license validation will automatically run for all web requests.

You can customize the cache duration in the middleware if you want to check less frequently:

```php
// To change to 48 hours (2 days) of caching
Cache::put($cacheKey, true, now()->addHours(48));

// Or apply to the entire web middleware group in app/Http/Kernel.php
protected $middlewareGroups = [
    'web' => [
        // Other middleware...
        \Tipusultan\LicenseValidator\Middleware\ValidateLicense::class,
    ],
];
```

### 3. Create error view

Create a view for license errors at `resources/views/license/mismatch.blade.php`:

This approach will:
1. Run your license validation for every web request
2. Honor the caching you've implemented
3. Not require any manual middleware registration
4. Work with all routes without modifying route files

Just be aware that since this middleware is global, it will run for every request, including assets, API calls, etc. You might want to add logic to skip certain paths or request types if needed.


```blade
<!DOCTYPE html>
<html>
<head>
    <title>License Validation Failed</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        .error-container {
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 30px;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        h1 {
            color: #d9534f;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>License Validation Failed</h1>
        <p>This application's license is not valid for this domain.</p>
        <p>Please contact the software provider to obtain a valid license.</p>
    </div>
</body>
</html>
```

## How It Works

This package validates your application license against a central validation server:

1. The middleware checks if the current domain is authorized
2. The validation result is cached for 24 hours for performance
3. Console commands bypass validation

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.