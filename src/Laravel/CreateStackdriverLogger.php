<?php

namespace CodeInternetApplications\MonologStackdriver\Laravel;

use Monolog\Logger;
use CodeInternetApplications\MonologStackdriver\StackdriverHandler;

class CreateStackdriverLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     * @return Logger
     */
    public function __invoke(array $config)
    {
        $logName              = $config['logName']              ?? '';
        $loggingClientOptions = $config['loggingClientOptions'] ?? [];
        $loggerOptions        = $config['loggerOptions']        ?? [];
        $entryOptionsWrapper  = $config['entryOptionsWrapper']  ?? 'stackdriver';
        $lineFormat           = $config['lineFormat']           ?? '%message%';
        $level                = $config['level']                ?? Logger::DEBUG;
        $bubble               = $config['bubble']               ?? true;

        $stackdriverHandler = new StackdriverHandler($logName, $loggingClientOptions, $loggerOptions, $entryOptionsWrapper, $lineFormat, $level, $bubble);

        $logger = new Logger('stackdriver', [$stackdriverHandler]);

        return $logger;
    }
}
