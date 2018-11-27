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
        // 'loggingClientOptions' => [
        //     'keyFilePath' => '/path/to/service-account-key-file.json',
        // ],
        // 'loggerOptions' => [],
        // 'lineFormat' => '%message%',
        // 'entryOptionsWrapper' => 'stackdriver',
    ],
]
```

## .env

Edit `.env` to update `LOG_CHANNEL` to `stackdriver`.
And finally add `GOOGLE_APPLICATION_CREDENTIALS` and `GOOGLE_CLOUD_PROJECT` or use loggingClientOptions to set path to key file and project id.

```
LOG_CHANNEL=stackdriver
```
