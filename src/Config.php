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
    private $appId = '';

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
    private $trace = false;

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
    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * @param string $appId
     * @return $this
     */
    public function setAppId($appId)
    {
        if (!is_string($appId) || !preg_match('/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}/', $appId)) {
            throw new \InvalidArgumentException();
        }

        $this->appId = $appId;
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
        $session = [];

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
        $request = [];

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
        $server = [];

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
    public function isTrace()
    {
        return $this->trace;
    }

    /**
     * @param boolean $trace
     * @return $this
     */
    public function setTrace($trace)
    {

        $this->trace = $trace;
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
     * @return \DateTime
     */
    public function getDateTimeFormatted()
    {
        $timezone = new \DateTimeZone(date_default_timezone_get() ?: 'UTC');

        $this->timeFormatted = \DateTime::createFromFormat('U.u', sprintf('%.6F', microtime(true)), $timezone)
            ->setTimezone($timezone)
            ->format('Y-m-d H:i:s');

        return $this->timeFormatted;
    }
}
