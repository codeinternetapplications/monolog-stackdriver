# Laravel/Laravel v5.6

## config/logging.php

Add the following entry to the `channels` section in the array below the existing entries.

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
        // 'loggerOptions' => [],    
        // 'entryOptionsWrapper' => 'stackdriver'
    ],
]
```

## .env

Edit `.env` to update `LOG_CHANNEL`.

```
LOG_CHANNEL=stackdriver
```
