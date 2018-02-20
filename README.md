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

Please read the documentation for the [Google\Cloud\Logging\LoggingClient](https://googlecloudplatform.github.io/google-cloud-php/#/docs/google-cloud/v0.54.0/logging/loggingclient?method=__construct) for other authentication options and further specific connection and setup.

## Pick your framework to continue

* [Laravel/Laravel v5.5](docs/laravel_laravel_v5_5.md)
* [Laravel/Laravel v5.6](docs/laravel_laravel_v5_6.md)
* [Laravel/Lumen v5.5](docs/laravel_lumen_v5_5.md)
* [Laravel/Lumen v5.6](docs/laravel_lumen_v5_6.md)