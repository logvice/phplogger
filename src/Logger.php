<?php namespace LogVice\PHPLogger;

/*
 * This file is part of \LogVice\PHPLogger package.
 *
 * (c) Alban Kora <ankdeveloper@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

use Psr\Log\LoggerInterface;

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
     * Logging levels from syslog protocol defined in RFC 5424
     *
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
     * @var Config $config
     */
    private $config;

    /**
     * @var Backtrace
     */
    private $backtrace;

    /**
     * @var array
     */
    private $logData = [];

    /**
     * Logger constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->backtrace = new Backtrace();
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
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     * @return boolean
     */
    public function alert($message, array $context = [])
    {
        return $this->output(self::ALERT, $message, $context);
    }

    /**
     * Critical conditions.
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     * @return boolean
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
     * @return boolean
     */
    public function error($message, array $context = [])
    {
        return $this->output(self::ERROR, $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     * @return boolean
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
     * @return boolean
     */
    public function notice($message, array $context = [])
    {
        return $this->output(self::NOTICE, $message, $context);
    }

    /**
     * Interesting events.
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     * @return boolean
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
     * @return boolean
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
     * @return boolean
     */
    public function log($level, $message, array $context = [])
    {
        return $this->output($level, $message, $context);
    }

    /**
     * Exception handler called internally by PHP's set_exception_handler
     *
     * @param \Exception $exception
     * @return boolean
     */
    public function handleException(\Exception $exception)
    {

        if ($this->config->isTrace()) {
            $this->backtrace->setTraces($exception->getTrace());
        }

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
     * @return boolean
     */
    public function handleError($errno, $errstr, $errfile = '', $errline = 0)
    {
        $errno = $this->convertErrorLevel($errno);

        return $this->output($errno, $errstr, ['file' => $errfile, 'line' => $errline]);
    }

    /**
     * shutdown called when the PHP process has finished running
     * should only be called internally by PHP's register_shutdown_function
     *
     * @return boolean
     */
    public function handleShutdown()
    {
        $error = error_get_last();

        if (is_null($error)) {
            return false;
        }

        $error['type'] = $this->convertErrorLevel($error['type']);

        if (array_key_exists($error['type'], $this->logLevels)) {
            return $this->output(
                $error['type'],
                $error['message'],
                [
                    'file' => $error['file'],
                    'line' => $error['line']
                ]
            );
        }

        return false;
    }

    /**
     * @param $errno
     * @return int
     */
    public function convertErrorLevel($errno)
    {
        switch ($errno) {
            case E_ERROR:
            case E_CORE_ERROR:
                $errno = static::CRITICAL;
                break;
            case E_USER_ERROR:
            case E_RECOVERABLE_ERROR:
                $errno = static::ERROR;
                break;
            case E_WARNING:
            case E_USER_WARNING:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
                $errno = static::WARNING;
                break;
            case E_PARSE:
            case E_COMPILE_ERROR:
                $errno = static::ALERT;
                break;
            case E_NOTICE:
            case E_STRICT:
            case E_DEPRECATED:
            case E_USER_NOTICE:
            case E_USER_DEPRECATED:
                $errno = static::NOTICE;
                break;
        }

        return $errno;
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
        if ($logLevel < $this->config->getLogLevel()) {
            return false;
        }

        $this->logData = [
            'appId' => $this->config->getAppId(),
            'channel' => $this->config->getChannel(),
            'environment' => $this->config->getEnvironment(),
            'message' => (string) $message,
            'context' => $context,
            'log_level' => $logLevel,
            'log_level_name' => $this->getLogLevelName($logLevel),
            'session' => $this->config->getSessionValues(),
            'request' => $this->config->getRequestValues(),
            'trace' => $this->config->isTrace() ? $this->backtrace->info() : '',
            'datetime' => $this->config->getDateTimeFormatted(),
        ];

        foreach ($this->config->getOutputHandlers() as $v) {
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
     * @return array
     */
    public function getLogData()
    {
        return $this->logData;
    }
}
