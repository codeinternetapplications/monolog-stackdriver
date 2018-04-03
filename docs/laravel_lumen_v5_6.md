# Laravel/Lumen v5.6

## bootstrap/app.php

Edit `bootstrap/app.php` and enable `$app->withFacades();`.

## config/logging.php

Make sure to have a [copy of config/logging.php from the Laravel/Laravel 5.6.* framework](https://github.com/laravel/laravel/blob/master/config/logging.php) stored in your project. Then you can add the following entry to the `channels` section in the array below the existing entries.

```php
'channels' => [

    // ( ... )

    'stackdriver' => [
        'driver' => 'custom',
        'via' => CodeInternetApplications\MonologStackdriver\Laravel\CreateStackdriverLogger::class,
        'logName' => 'my-project-log',
        'loggingClientOptions' => [
            'keyFilePath' => '/path/to/service-account-key-file.json',
        ],
        // 'entryOptionsWrapper' => 'stackdriver'
    ],
]
```

## .env

Finally, edit `.env` to update `LOG_CHANNEL` to `stackdriver`.

```
LOG_CHANNEL=stackdriver
```
