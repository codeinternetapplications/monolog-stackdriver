# MonologStackdriver

This package enables you to push your [Monolog](https://packagist.org/packages/monolog/monolog) log entries to [Stackdriver](https://cloud.google.com/stackdriver) which is part of the [Google Cloud Platform](https://cloud.google.com).

The supplied `StackdriverHandler` copies the given log level into the Stackdriver's severity based on your log method.

It also respects the context argument which allows you to send extra contextual data with your log message. This will be stored in the log message under `jsonPayload.data`.

---

# Configuration

## Service account

With our samples we assume you have a service account Google Developers Console JSON key file available within your project to point at with read rights.

If you don't have this file yet, you can create it via [Google Cloud Platform - IAM & Admin - Service accounts](https://console.cloud.google.com/iam-admin/serviceaccounts). Please make sure you have at least the role of `Logs writer` enabled.

## Google\Cloud\Logging\LoggingClient options

Please read the documentation for the [Google\Cloud\Logging\LoggingClient](https://googlecloudplatform.github.io/google-cloud-php/#/docs/google-cloud/v0.61.0/logging/loggingclient?method=__construct) for other authentication options and further specific connection and setup.

## Google\Cloud\Logging\Logger options

Please read the documentation for the [Google\Cloud\Logging\Logger setup via Google\Cloud\Logging\LoggingClient](https://googlecloudplatform.github.io/google-cloud-php/#/docs/google-cloud/v0.61.0/logging/loggingclient?method=logger) for specific details about these options.

This set of options will allow you to set the default resource type and it's related labels that apply to all the logs. Please read [Method: monitoredResourceDescriptors.list](https://cloud.google.com/logging/docs/reference/v2/rest/v2/monitoredResourceDescriptors/list) and do the "Try this API" to get a full list of the specific labels per resource.

You can override these at runtime by calling `appendLoggerOptions`/`setLoggerOptions` on your `StackdriverHandler` instance.

## Google\Cloud\Logging\Entry options

Please read the documentation for the [Google\Cloud\Logging\Entry setup via Google\Cloud\Logging\Logger](http://googlecloudplatform.github.io/google-cloud-php/#/docs/google-cloud/v0.61.0/logging/logger?method=entry) for specific details about these options.

By default, you can add Stackdriver specific log entry options by adding these wrapped in the `stackdriver`-key inside the context array. Very useful to add log entry specific labels for instance.

```php
$context['stackdriver'] = [
    // stackdriver related entry options
];
```

If you need to, you can override this key name by setting `$entryOptionsWrapper` to your own value (string) when using `StackdriverHandler::__construct`.

## Pick your framework for some specific setup

* [Laravel/Laravel v5.5](docs/laravel_laravel_v5_5.md)
* [Laravel/Laravel v5.6](docs/laravel_laravel_v5_6.md)
* [Laravel/Laravel v5.7](docs/laravel_laravel_v5_7.md)
* [Laravel/Lumen v5.5](docs/laravel_lumen_v5_5.md)
* [Laravel/Lumen v5.6](docs/laravel_lumen_v5_6.md)
* [Laravel/Lumen v5.7](docs/laravel_lumen_v5_7.md)

# Vanilla usage

```php
use Monolog\Logger;
use CodeInternetApplications\MonologStackdriver\StackdriverHandler;

// ( ... )

// GCP Project ID
$projectId = 'eg-my-project-id-148223';

// See Google\Cloud\Logging\LoggingClient::__construct
$loggingClientOptions = [
    'keyFilePath' => '/path/to/service-account-key-file.json'
];

// init handler
$stackdriverHandler = new StackdriverHandler(
    $projectId,
    $loggingClientOptions
);

// init logger with StackdriverHandler
$logger = new Logger('stackdriver', [$stackdriverHandler]);

// basic info log with contextual data
$logger->info('New order', ['orderId' => 1001]);
```

```php
// ( ... )

// add specific log entry options, eg labels
$context = ['orderId' => 1001];

// add a 'stackdriver' entry to the context to append
// log entry specific options
$context['stackdriver'] = [
    'labels' => [
        'action' => 'paid'
    ]
];
$logger->info('Order update', $context);
```

```php
// ( ... )

// add specific log entry options, eg labels and operation
$context = ['orderId' => 1001];
$context['stackdriver'] = [
    'labels' => [
        'order' => 'draft'
    ],
    'operation' => [
        'id' => 'order-1001',
        'first' => true,
        'last' => false
    ]
];
$logger->info('Order update', $context);

// update both label and operation
$context['stackdriver'] = [
    'labels' => [
        'order' => 'paid'
    ],
    'operation' => [
        'id' => 'order-1001',
        'first' => false,
        'last' => false
    ]
];
$logger->info('Order update', $context);

// update both label and operation again
$context['stackdriver'] = [
    'labels' => [
        'order' => 'fulfilled'
    ],
    'operation' => [
        'id' => 'order-1001',
        'first' => false,
        'last' => true
    ]
];
$logger->info('Order update', $context);
```
