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
     * A context array key used to take log entry options from
     * @var string
     */
    private $entryOptionsWrapper;

    /**
     * Log entry options (all but severity) as supported by Google\Cloud\Logging\Logger::entry
     * @var array Entry options.
     */
    private $entryOptionsAllowedKeys = [
        'resource' => [],
        'httpRequest' => [],
        'labels' => [],
        'operation' => [],
        'insertId' => '',
        'timestamp' => '',
    ];


    /**
     * Default logger options to use (Google\Cloud\Logging\LoggingClient::logger valid options). Will be merged with entryOptions when logging
     * @var array Logger options.
     */
    private $loggerOptions;

    /**
     * @param string  $logName              Name of your log
     * @param array   $loggingClientOptions Google\Cloud\Logging\LoggingClient valid options
     * @param array   $loggerOptions        Google\Cloud\Logging\LoggingClient::logger valid options
     * @param string  $entryOptionsWrapper  Array key used in the context array to take Google\Cloud\Logging\Entry options from
     * @param string  $lineFormat           Monolog\Formatter\LineFormatter format
     * @param int     $level                The minimum logging level at which this handler will be triggered
     * @param Boolean $bubble               Whether the messages that are handled can bubble up the stack or not
     */
    public function __construct($logName, $loggingClientOptions, $loggerOptions = [], $entryOptionsWrapper = 'stackdriver', $lineFormat = '%message%', $level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);

        $this->logger              = (new LoggingClient($loggingClientOptions))->logger($logName);
        $this->loggerOptions       = (array) $loggerOptions;
        $this->formatter           = new LineFormatter($lineFormat);
        $this->entryOptionsWrapper = $entryOptionsWrapper;
    }

    public function appendLoggerOptions($key, $value)
    {
        return $this->setLoggerOptions($key, $value, true);
    }

    public function setLoggerOptions($key, $value, $append = false)
    {
        if (!array_key_exists($key, $this->entryOptionsAllowedKeys)) {
            throw new \Exception('Unsupported logger option ' . $key);
        }

        if ($append) {
            $this->loggerOptions[$key] = ($this->loggerOptions[$key] ?? $this->entryOptionsAllowedKeys[$key]) + $value;
        } else {
            $this->loggerOptions[$key] = $value;
        }

        return true;
    }

    /**
     * Writes the record down to the log
     *
     * @param  array $record
     * @return void
     */
    protected function write(array $record)
    {
        $options = $this->getOptionsFromRecord($record);

        $data = [
            'message' => $record['formatted'],
            'data'    => $record['context']
        ];

        $entry = $this->logger->entry($data, $options);

        $this->logger->write($entry);
    }

    /**
     * Get the Google\Cloud\Logging\Entry options
     *
     * @param  array $record by reference
     * @return array $options
     */
    private function getOptionsFromRecord(array &$record)
    {
        $options = $this->loggerOptions + [
            'severity' => $record['level_name']
        ];

        if (isset($record['context'][$this->entryOptionsWrapper])) {
            foreach (array_keys($this->entryOptionsAllowedKeys) as $entryOption) {
                if ($record['context'][$this->entryOptionsWrapper][$entryOption] ?? false) {
                    $options[$entryOption] = ($options[$entryOption] ?? []) + $record['context'][$this->entryOptionsWrapper][$entryOption];
                }
            }
            unset($record['context'][$this->entryOptionsWrapper]);
        }

        return $options;
    }
}
