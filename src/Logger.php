<?php

namespace LogVice\PHPLogger;

use Psr\Log\LoggerInterface;
use LogVice\PHPLogger\Contracts\InformationInterface;
use LogVice\PHPLogger\Contracts\OutputInterface;

class Logger implements LoggerInterface
{
    /**
     * Detailed debug information
     */
    const DEBUG = 100;

    /**
     * Interesting events
     */
    const INFO = 200;

    /**
     * Uncommon events
     */
    const NOTICE = 250;

    /**
     * Exceptional occurrences that are not errors
     */
    const WARNING = 300;

    /**
     * Runtime errors
     */
    const ERROR = 400;

    /**
     * Critical conditions
     */
    const CRITICAL = 500;

    /**
     * Action must be taken immediately
     */
    const ALERT = 550;

    /**
     * Urgent alert.
     */
    const EMERGENCY = 600;

    /**
     * @var string
     */
    protected $appId = '';

    /**
     * @var string
     */
    protected $timezone = '';

    /**
     * @var array
     */
    protected $logData = [];

    /**
     * @var string
     */
    protected $channel;

    /**
     * @var string
     */
    protected $timeFormatted = '';

    /**
     * @var OutputInterface[]
     */
    protected $outputs = [];

    /**
     * @var InformationInterface[]
     */
    protected $information = [];

    /**
     * Logging levels from syslog protocol defined in RFC 5424
     * @var array
     */
    protected $logLevels = [
        100 => 'DEBUG',
        200 => 'INFO',
        250 => 'NOTICE',
        300 => 'WARNING',
        400 => 'ERROR',
        500 => 'CRITICAL',
        550 => 'ALERT',
        600 => 'EMERGENCY',
    ];

    /**
     * The level of logs to be collected
     */
    protected $logLevel;

    /**
     * @param $channel
     * @param int $logLevel
     */
    public function __construct($channel = 'main', $logLevel = self::DEBUG)
    {
        $this->channel = $channel;
        $this->logLevel = $logLevel;
    }

    /**
     * @param string $appId
     */
    public function setAppId($appId)
    {
        $this->appId = $appId;
    }

    /**
     * Set outputs, replacing all existing ones.
     *
     * @param mixed $outputs
     * @return $this
     * @throw InvalidArgumentException
     */
    public function setOutputs($outputs)
    {
        if (is_object($outputs)) {
            $outputs = [$outputs];
        }

        if (!is_array($outputs)) {
            throw new \InvalidArgumentException('Outputs parameter should be an object or an array');
        }

        $this->outputs = $outputs;
        return $this;
    }

    /**
     * @return Contracts\OutputInterface[]
     */
    public function getOutputs()
    {
        return $this->outputs;
    }

    /**
     * Set information, replacing all existing ones.
     *
     * @param mixed $information
     * @return $this
     * @throw InvalidArgumentException
     */
    public function setInformation($information)
    {
        if (is_object($information)) {
            $information = [$information];
        }

        if (!is_array($information)) {
            throw new \InvalidArgumentException('Information parameter should be an object or an array');
        }

        $this->information = $information;
        return $this;
    }

    /**
     * @return Contracts\InformationInterface[]
     */
    public function getInformation()
    {
        return $this->information;
    }

    /**
     * @return array
     */
    public function getLogData()
    {
        return $this->logData;
    }

    /**
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @return string
     */
    public function getTimeFormatted()
    {
        return $this->timeFormatted;
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function emergency($message, array $context = [])
    {
        return $this->output(self::EMERGENCY, $message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function alert($message, array $context = [])
    {
        return $this->output(self::ALERT, $message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function critical($message, array $context = [])
    {
        return $this->output(self::CRITICAL, $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function error($message, array $context = [])
    {
        return $this->output(self::ERROR, $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function warning($message, array $context = [])
    {
        return $this->output(self::WARNING, $message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function notice($message, array $context = [])
    {
        return $this->output(self::NOTICE, $message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function info($message, array $context = [])
    {
        return $this->output(self::INFO, $message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function debug($message, array $context = [])
    {
        return $this->output(self::DEBUG, $message, $context);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function log($level, $message, array $context = [])
    {
        return $this->output($level, $message, $context);
    }

    /**
     * Exception handler called internally by PHP's set_exception_handler
     *
     * @param \Exception $exception
     * @return bool
     */
    public function handleException(\Exception $exception)
    {
        return $this->output(
            static::ERROR,
            $exception->getMessage(),
            [
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]
        );
    }

    /**
     * handlerError called internally by PHP's set_error_handler
     *
     * @param int $errno
     * @param string $errstr
     * @param string $errfile
     * @param int $errline
     * @return mixed
     */
    public function handleError($errno, $errstr, $errfile = '', $errline = 0)
    {
        switch ($errno) {
            case E_USER_ERROR:
                $errno = static::ERROR;
                break;
            case E_USER_WARNING:
                $errno = static::WARNING;
                break;
            case E_USER_NOTICE:
                $errno = static::NOTICE;
                break;
        }

        return $this->output($errno, $errstr, ['file' => $errfile, 'line' => $errline]);
    }

    /**
     * shutdown called when the PHP process has finished running
     * should only be called internally by PHP's register_shutdown_function
     *
     * @return mixed
     */
    public function handleShutdownError()
    {
        $error = error_get_last();

        if (!is_null($error) && $error['type'] === E_ERROR) {

            return $this->output(
                static::ERROR,
                $error['message'],
                [
                    'file' => $error['file'],
                    'line' => $error['line']
                ]
            );
        }

        return null;
    }

    /**
     * Send the log data to registered outputs
     *
     * @param $logLevel
     * @param $message
     * @param array $context
     * @throws \InvalidArgumentException
     * @return bool
     */
    protected function output($logLevel, $message, array $context = [])
    {
        if ($logLevel < $this->logLevel) {
            return false;
        }

        $this->logData = [
            'appId' => $this->appId,
            'channel' => $this->channel,
            'message' => (string)$message,
            'context' => $context,
            'log_level' => $logLevel,
            'log_level_name' => $this->getLogLevelName($logLevel),
            'datetime' => $this->getDateTimeFormatted(),
        ];

        foreach ($this->information as $v) {
            $this->logData['extra'][$v->getClassName()] = $v->info();
        }

        foreach ($this->outputs as $v) {
            $v->send(json_encode($this->logData));
        }

        return true;
    }

    /**
     * Get log level name to uppercase
     *
     * @param int $level
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getLogLevelName($level)
    {
        if (array_key_exists($level, $this->logLevels) === false) {
            throw new \InvalidArgumentException(
                'Level ' . $level . ' is not inside: ' . implode(', ', array_keys($this->logLevels))
            );
        }

        return $this->logLevels[$level];
    }

    /**
     * Get date time on appropriate format
     *
     * @return \DateTime
     */
    protected function getDateTimeFormatted()
    {
        $timezone = new \DateTimeZone(date_default_timezone_get() ?: 'UTC');

        $this->timeFormatted = \DateTime::createFromFormat('U.u', sprintf('%.6F', microtime(true)), $timezone)
            ->setTimezone($timezone)
            ->format('Y-m-d H:i:s');

        return $this->timeFormatted;
    }
}
