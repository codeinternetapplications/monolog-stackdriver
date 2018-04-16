# Laravel/Laravel v5.5

## bootstrap/app.php

Edit `bootstrap/app.php` and add the following lines to the file just above `return $app` at the end of the file.

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

return $app;
```

## .env

Finally, edit `.env` to add a new/update entry `APP_LOG` with the value `stackdriver`.

```
APP_LOG=stackdriver
```
