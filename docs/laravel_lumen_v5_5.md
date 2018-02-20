# Laravel/Lumen v5.5

## bootstrap/app.php

Edit `bootstrap/app.php` and enable `$app->withFacades();`.

Then add the following lines to the file.

```php
// Configure Monolog being able to log to Stackdriver
use CodeInternetApplications\MonologStackdriver\StackdriverHandler;
$app->configureMonologUsing(function ($monolog) {
    // set vars
    $logName = 'my-project-log';
    $loggingClientOptions = [
        'keyFilePath' => '/path/to/service-account-key-file.json'
    ];

    // init
    $stackdriverHandler = new StackdriverHandler($logName, $loggingClientOptions);
    $monolog->pushHandler($stackdriverHandler);

    return $monolog;
});

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
```
