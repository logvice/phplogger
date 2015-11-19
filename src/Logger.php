<?php

/*
 * This file is part of \LogVice\PHPLogger package.
 *
 * (c) Alban Nikolaos Kora <ankdeveloper@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

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
    protected $logData = array();

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
    protected $outputs = array();

    /**
     * @var InformationInterface[]
     */
    protected $information = array();

    /**
     * Logging levels from syslog protocol defined in RFC 5424
     * @var array
     */
    protected static $levels = array(
        100 => 'DEBUG',
        200 => 'INFO',
        250 => 'NOTICE',
        300 => 'WARNING',
        400 => 'ERROR',
        500 => 'CRITICAL',
        550 => 'ALERT',
        600 => 'EMERGENCY',
    );

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
    public function emergency($message, array $context = array())
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
    public function alert($message, array $context = array())
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
    public function critical($message, array $context = array())
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
    public function error($message, array $context = array())
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
    public function warning($message, array $context = array())
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
    public function notice($message, array $context = array())
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
    public function info($message, array $context = array())
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
    public function debug($message, array $context = array())
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
    public function log($level, $message, array $context = array())
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
            $exception->getCode(),
            $exception->getMessage(),
            array(
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            )
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
        return $this->output($errno, $errstr, array('file' => $errfile, 'line' => $errline));
    }

    /**
     * shutdown called when the PHP process has finished running
     * should only be called internally by PHP's register_shutdown_function
     *
     * @return mixed
     */
    public function handleShutdownError()
    {
        $lastError = error_get_last();

        if (!is_null($lastError)) {
            return $this->output(
                $lastError['type'],
                $lastError['message'],
                array('file' => $lastError['file'], 'line' => $lastError['line'])
            );
        }

        return null;
    }

    /**
     * Send the log data to registered outputs
     *
     * @param $level
     * @param $message
     * @param array $context
     * @throws \InvalidArgumentException
     * @return bool
     */
    protected function output($level, $message, array $context = array())
    {
        if ($level < $this->logLevel) {
            return false;
        }

        $this->logData = array(
            'appId' => $this->appId,
            'channel' => $this->channel,
            'message' => (string)$message,
            'context' => $context,
            'level' => $level,
            'level_name' => static::getLevelName($level),
            'datetime' => $this->getDateTimeFormatted(),
        );

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
    public static function getLevelName($level)
    {
        if (array_key_exists($level, static::$levels) === false) {
            throw new \InvalidArgumentException('Level "' . $level . '" is not inside: ' . implode(', ', array_keys(static::$levels)));
        }

        return static::$levels[$level];
    }

    /**
     * Get datetime on appropriate format
     *
     * @return \DateTime
     */
    protected function getDateTimeFormatted()
    {
        $timezone = new \DateTimeZone(date_default_timezone_get() ?: 'UTC');

        $this->timeFormatted = \DateTime::createFromFormat('U.u', sprintf('%.6F', microtime(true)), $timezone)
            ->setTimezone($timezone)
            ->format("Y-m-d H:i:s");

        return $this->timeFormatted;
    }
}
