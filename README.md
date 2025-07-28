# Logger Package for Laravel

## Introduction
The Logger package provides advanced logging capabilities for Laravel applications. It allows you to easily record messages at various levels (such as warning, error, debug, etc.), log SQL queries, and store logs through a custom service. This package supports multiple logging channels, formats backtrace information, and integrates smoothly with Laravel's existing logging system.

**Compatibility**: Laravel 10.x, 11.x, and 12.x

## Installation
```bash
  composer require phongtran/logger
```
After installation, you can publish the configuration file if needed:
```bash
  php artisan vendor:publish --tag=logger
```

## Configuration

Make sure the required parameters are configured in the config/logger.php or .env file. You can add custom channels or use the default channels available in the package.

```.env
    ENABLE_QUERY_DEBUGGER=true
    ENABLE_QUERY_DEBUGGER_PRODUCTION=false
    LOGGER_MIN_QUERY_TIME=0
```

## Usage
#### Logging Messages
To log various messages, use the Logger class. The package supports logging at different levels such as warning, error, debug, and info.

#### Log a Warning Message

```php
use phongtran\Logger\Logger;

Logger::warning('This is a warning message.');
```

#### Log an Exception Message

```php
use phongtran\Logger\Logger;

Logger::exception('Error occurred: Database connection failed.');
```
#### Log a Fatal Error Message

```php
use phongtran\Logger\Logger;

Logger::fatal('A critical error occurred while processing the request.');
```

#### Log Debug Information

```php
use phongtran\Logger\Logger;

Logger::debug('Debugging application state: User has logged in.');
```

#### Log an Info Message

```php
use phongtran\Logger\Logger;

Logger::info('User profile updated successfully.');
```

#### Log SQL Queries
To log SQL queries, you just need to add the configuration in the .env file. The Logger will listen to all queries and store them in the log_queries table (by default).

```.env
ENABLE_QUERY_DEBUGGER=true
```

#### Log Activities
Log all application requests, including the URL, parameters, response, and the execution time of the request. The activity log is configured in the `activity` middleware. To activate this middleware, you just need to add it to your routes.

```php
Route::group(['middleware' => 'activity'], function () {
    Route::get('/', [HomeController::class, 'index']);
});
```

#### HTTP Exception
Add this line in Handle Exceptions (bootstrap/app.php)

**For Laravel 12:**
```php
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
    })
    ->withExceptions(function (Exceptions $exceptions) {
        \phongtran\Logger\LoggerHandler::handle($exceptions); //Log exceptions
    })->create();
```

**For Laravel 11:**
```php
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
    })
    ->withExceptions(function (Exceptions $exceptions) {
        \phongtran\Logger\LoggerHandler::handle($exceptions); //Log exceptions
    })->create();
```

**For Laravel 10 and below:**
```php
// In app/Exceptions/Handler.php
public function register(): void
{
    $this->reportable(function (Throwable $e) {
        \phongtran\Logger\LoggerHandler::handleLegacy($this);
    });
}
```

## Advanced Features
#### Backtrace Formatting

The Logger automatically adds backtrace information (file and line number) to logs to make debugging easier. This allows you to track the location where the log is generated in the code.

#### Debug Routes

If you're having issues with the `/logger` routes, you can debug them using:

```php
// In your application
dd(logger_routes_debug());

// Or check routes in tinker
php artisan tinker
>>> logger_routes_debug()
```

This will show you all registered logger routes and help identify any issues.

Example of a log message with backtrace:

```php
<app/Http/Controllers/HomeController.php (Line:42)> This is a debug message.
```

This helps you pinpoint exactly where the log message was triggered in your code.

## Contributions
We welcome contributions from the community! If you would like to contribute, please fork the repository and submit a pull request with your improvements or bug fixes

## License
This package is licensed under the [MIT license](https://opensource.org/licenses/MIT). Please refer to the LICENSE file for more details.

## Author
#### Phong Tran
Email: [jarvis.phongtran@gmail.com](https://github.com/mockingbitch)

GitHub: [github/mockingbitch](https://github.com/mockingbitch)
