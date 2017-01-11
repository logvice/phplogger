<?php namespace LogVice\PHPLogger;

/*
 * This file is part of \LogVice\PHPLogger package.
 *
 * (c) Alban Kora <ankdeveloper@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

use LogVice\PHPLogger\Output\OutputContract;

class Config
{
    /**
     * @var string
     */
    private $appKey = '';

    /**
     * @var string
     */
    private $environment = '';

    /**
     * @var string
     */
    private $channel = 'PHP-Logs';

    /**
     * @var \LogVice\PHPLogger\Output\OutputContract[]
     */
    private $outputHandlers = [];

    /**
     * @var array
     */
    private $sessionValues = [];

    /**
     * @var array
     */
    private $requestValues = [];

    /**
     * @var array
     */
    private $serverValues = [];

    /**
     * @var bool
     */
    private $backtrace = false;

    /**
     * @var int
     */
    private $logLevel = Logger::DEBUG;

    /**
     * @var string
     */
    protected $timeFormatted = '';

    /**
     * @return string UUID
     */
    public function getAppKey()
    {
        return $this->appKey;
    }

    /**
     * @param string $appKey
     * @return $this
     */
    public function setAppKey($appKey)
    {
        if (!is_string($appKey) || !ctype_alnum($appKey) || strlen($appKey) !== 40) {
            throw new \InvalidArgumentException();
        }

        $this->appKey = $appKey;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param $environment
     * @return $this
     */
    public function setEnvironment($environment)
    {
        if (!is_string($environment)) {
            throw new \InvalidArgumentException();
        }

        $this->environment = $environment;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param $channel
     * @return $this
     */
    public function setChannel($channel)
    {
        if (!is_string($channel)) {
            throw new \InvalidArgumentException();
        }

        $this->channel = $channel;
        return $this;
    }

    /**
     * @return \LogVice\PHPLogger\Output\OutputContract[]
     */
    public function getOutputHandlers()
    {
        return $this->outputHandlers;
    }

    /**
     * @param array $outputHandlers
     * @return $this
     */
    public function setOutputHandlers(array $outputHandlers)
    {
        $this->outputHandlers = [];

        foreach ($outputHandlers as $output) {
            if (!$output instanceof OutputContract) {
                throw new \InvalidArgumentException();
            }

            $this->outputHandlers[] = $output;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getSessionValues()
    {
        $session = null;

        if (!empty($this->sessionValues)) {
            foreach ($this->sessionValues as $sessionKey) {
                if (isset($_SESSION[$sessionKey])) {
                    $session[$sessionKey] = $_SESSION[$sessionKey];
                }
            }
        }

        return $session;
    }

    /**
     * @param array $sessionValues
     * @return $this
     */
    public function setSessionValues(array $sessionValues)
    {
        $this->sessionValues = $sessionValues;
        return $this;
    }

    /**
     * @return array
     */
    public function getRequestValues()
    {
        $request = null;

        if (!empty($this->requestValues)) {
            foreach ($this->requestValues as $requestKey) {
                if (isset($_REQUEST[$requestKey])) {
                    $request[$requestKey] = $_REQUEST[$requestKey];
                }
            }
        }

        return $request;
    }

    /**
     * @param array $requestValues
     * @return $this
     */
    public function setRequestValues(array $requestValues)
    {
        $this->requestValues = $requestValues;
        return $this;
    }

    /**
     * @return array
     */
    public function getServerValues()
    {
        $server = null;

        if (!empty($this->serverValues)) {
            foreach ($this->serverValues as $key) {
                if (isset($_SERVER[$key])) {
                    $server[$key] = $_SERVER[$key];
                }
            }
        }

        return $server;
    }

    /**
     * @param array $serverValues
     */
    public function setServerValues(array $serverValues)
    {
        $this->serverValues = $serverValues;
    }

    /**
     * @return boolean
     */
    public function backtraceStatus()
    {
        return $this->backtrace;
    }

    /**
     * @return $this
     */
    public function activateBacktrace()
    {
        $this->backtrace = true;
        return $this;
    }

    /**
     * @return int
     */
    public function getLogLevel()
    {
        return $this->logLevel;
    }

    /**
     * @param int $logLevel
     * @return $this
     */
    public function setLogLevel($logLevel = Logger::DEBUG)
    {
        $this->logLevel = $logLevel;
        return $this;
    }

    /**
     * Get date time on appropriate format
     * @return string
     */
    public function getDateTimeFormatted()
    {
        $timezone = new \DateTimeZone(date_default_timezone_get() ?: 'UTC');

        $this->timeFormatted = \DateTime::createFromFormat('U.u', sprintf('%.6F', microtime(true)), $timezone)
            ->setTimezone($timezone)
            ->format('Y-m-d H:i:s');

        return $this->timeFormatted;
    }

    public function getExtraValues(array $extras = [])
    {
        $extras['session'] = $this->getSessionValues();
        $extras['server'] = $this->getServerValues();
        $extras['request'] = $this->getRequestValues();

        return json_encode($extras);
    }
}
