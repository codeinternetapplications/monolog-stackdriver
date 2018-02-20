<?php

namespace CodeInternetApplications\MonologStackdriver;

use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Google\Cloud\Logging\LoggingClient;
use Monolog\Handler\AbstractProcessingHandler;

/**
 * StackdriverHandler
 * 
 * @author Martin van Dam <martin@code.nl>
 * @author Wouter Monkhorst <wouter@code.nl>
 */
class StackdriverHandler extends AbstractProcessingHandler
{
    /** 
     * The Stackdriver logger
     * @var Google\Cloud\Logging\Logger
     */
    private $logger;

    /**
     * @param string  $logName              Name of your log
     * @param array   $loggingClientOptions Google\Cloud\Logging\LoggingClient valid options
     * @param int     $level                The minimum logging level at which this handler will be triggered
     * @param Boolean $bubble               Whether the messages that are handled can bubble up the stack or not
     */
    public function __construct($logName, $loggingClientOptions, $level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);

        $this->logger = (new LoggingClient($loggingClientOptions))->logger($logName);
        $this->formatter = new LineFormatter('%message%');
    }

    /**
     * Writes the record down to the log
     *
     * @param  array $record
     * @return void
     */
    protected function write(array $record)
    {
        $data = [
            'message' => $record['message'],
            'data'    => $record['context']
        ];

        $entry = $this->logger->entry($data, [
            'severity' => $record['level_name']
        ]);

        $this->logger->write($entry);
    }
}
