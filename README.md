# MonologStackdriver

This package enables you to push your Monolog log entries to Stackdriver which is part of the Google Cloud Platform.

The supplied `StackdriverHandler` copies the given log level into the Stackdriver's severity based on your log method.

It also respects the context argument which allows you to send extra contextual data with your log message. This will be stored in the log message under `jsonPayload.data`.

---

# Configuration

## Service account

With this sample we assume you have a service account Google Developers Console JSON key file available within your project to point at with read rights.

If you don't have this file yet, you can create it via [Google Cloud Platform - IAM & Admin - Service accounts](https://console.cloud.google.com/iam-admin/serviceaccounts). Please make sure you have at least the role of `Logs writer` enabled.

## Google\Cloud\Logging\LoggingClient options

Please read the documentation for the [Google\Cloud\Logging\LoggingClient](https://googlecloudplatform.github.io/google-cloud-php/#/docs/google-cloud/v0.54.0/logging/loggingclient?method=__construct) for other authentication options and further specific connection and setup.

## Laravel/Lumen v5.5.x

### bootstrap/app.php

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

## Laravel/Lumen v5.6.x

### bootstrap/app.php

Edit `bootstrap/app.php` and enable `$app->withFacades();`.

### config/logging.php

Make sure to have a [copy of config/logging.php from the Laravel/Laravel 5.6.* framework](https://github.com/laravel/laravel/blob/master/config/logging.php) stored in your project. Then you can add the following entry to the `channels` section in the array below the existing entries.

```
'channels' => [

    // ( ... )

    'stackdriver' => [
        'driver' => 'custom',
        'via' => CodeInternetApplications\MonologStackdriver\Laravel\CreateStackdriverLogger::class,
        'logName' => 'my-project-log',
        'loggingClientOptions' => [
            'keyFilePath' => '/path/to/service-account-key-file.json',
        ]
    ],
]
```
