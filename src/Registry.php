<?php

namespace LogVice\PHPLogger;

class Registry
{
    /**
     * List of all loggers in the registry (by named indexes)
     *
     * @var Logger[]
     */
    private static $loggers = array();

    /**
     * @param Logger $logger
     * @param null $channel
     * @param bool $overwrite
     * @throws \InvalidArgumentException
     */
    public static function addLogger(Logger $logger, $channel = null, $overwrite = false)
    {
        $channel = $channel ?: $logger->getChannel();

        if (isset(self::$loggers[$channel]) && !$overwrite) {
            throw new \InvalidArgumentException('Logger with the given name already exists');
        }

        self::$loggers[$channel] = $logger;
    }

    /**
     * Checks if such logging channel exists by name or instance
     *
     * @param $logger
     * @return bool
     */
    public static function hasLogger($logger)
    {
        if ($logger instanceof Logger) {
            $index = array_search($logger, self::$loggers, true);
            return false !== $index;
        }

        return isset(self::$loggers[$logger]);
    }

    /**
     * Removes instance from registry by name or instance
     *
     * @param string|Logger $logger Name or logger instance
     */
    public static function removeLogger($logger)
    {
        if ($logger instanceof Logger) {
            if (false !== ($idx = array_search($logger, self::$loggers, true))) {
                unset(self::$loggers[$idx]);
            }
        } else if (array_key_exists($logger, self::$loggers)) {
            unset(self::$loggers[$logger]);
        } else {
            throw new \InvalidArgumentException("Logger with the given name don't exists");
        }
    }

    /**
     * Clears the registry
     */
    public static function clear()
    {
        self::$loggers = array();
    }

    /**
     * @param $name
     * @return Logger
     * @throws \InvalidArgumentException
     */
    public static function getInstance($name)
    {
        if (array_key_exists($name, self::$loggers) === false) {
            throw new \InvalidArgumentException(sprintf('Requested "%s" logger instance is not in the registry', $name));
        }

        return self::$loggers[$name];
    }

    /**
     * @param $name
     * @param $arguments
     * @return Logger
     * @throws \InvalidArgumentException
     */
    public static function __callStatic($name, $arguments)
    {
        return self::getInstance($name);
    }
}
