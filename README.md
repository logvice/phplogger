# LogVice\PHPLogger - PHP Logging made easy.

[![Build Status](https://img.shields.io/travis/logvice/phplogger.svg)](https://travis-ci.org/logvice/phplogger)
[![Coverage Status](https://coveralls.io/repos/logvice/phplogger/badge.svg?branch=master&service=github)](https://coveralls.io/github/logvice/phplogger?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/logvice/phplogger.svg?style=flat-square)](https://packagist.org/packages/logvice/phplogger)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

LogVice\PHPLogger sends your logs to LogVice.com service or to your own LogVice platform installation. This library implements the [PSR-3](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md) standards.

## Installation

Install the latest version

```bash
composer require logvice/phplogger
```

## Basic Usage

```php
<?php

use LogVice\PHPLogger\Logger;
use LogVice\PHPLogger\Config;
use LogVice\PHPLogger\Output\TCPOutput;
use LogVice\PHPLogger\Output\UDPOutput;

// create a config instance
$config = new Config();
$config->setAppKey('xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
$config->setEnvironment('dev');
$config->setChannel('php');
$config->activateBacktrace();
$config->setOutputHandlers([
    new TCPOutput('127.0.0.1', '8080'),
    new UDPOutput('127.0.0.1', '514'),
]);
$config->setLogLevel(Logger::DEBUG);

// create a log instance
$log = new Logger($config);

// add records to the log
$log->debug('foo');
$log->info('bar');
$log->notice('foo');
$log->warning('bar');
$log->error('foo');
$log->critical('bar');
$log->alert('foo');
$log->emergency('bar');
$log->log(Logger::ERROR, 'foo');
```

## Register Error, Exception and Shutdown handlers

```php
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    // create a config instance
    $config = new LogVice\PHPLogger\Config();
    $config->setAppKey('xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
    $config->setEnvironment('dev');
    $config->setChannel('php');
    $config->setOutputHandlers([
        new LogVice\PHPLogger\Output\TCPOutput('127.0.0.1', '8080'),
        new LogVice\PHPLogger\Output\UDPOutput('127.0.0.1', '514'),
        new LogVice\PHPLogger\Output\FileOutput('path/to/logs', 'file-name', true)
    ]);
    $config->setLogLevel(Logger::ERROR);

    // create a log instance
    $logger = new LogVice\PHPLogger\Logger($config);

    $logger->handleError($errno, $errstr, $errfile, $errline);
});

set_exception_handler(function ($exception) {
    // create a config instance
    $config = new LogVice\PHPLogger\Config();
    $config->setAppKey('xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
    $config->setEnvironment('dev');
    $config->setChannel('php');
    $config->setOutputHandlers([
        new LogVice\PHPLogger\Output\TCPOutput('127.0.0.1', '8080'),
        new LogVice\PHPLogger\Output\UDPOutput('127.0.0.1', '514'),
        new LogVice\PHPLogger\Output\FileOutput('path/to/logs', 'file-name', true)
    ]);
    $config->setLogLevel(Logger::ERROR);

    // create a log instance
    $logger = new LogVice\PHPLogger\Logger($config);

    $logger->handleException($exception);
});

register_shutdown_function(function () {
    // create a config instance
    $config = new LogVice\PHPLogger\Config();
    $config->setAppKey('xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
    $config->setEnvironment('dev');
    $config->setChannel('php');
    $config->setOutputHandlers([
        new LogVice\PHPLogger\Output\TCPOutput('127.0.0.1', '8080'),
        new LogVice\PHPLogger\Output\UDPOutput('127.0.0.1', '514'),
        new LogVice\PHPLogger\Output\FileOutput('path/to/logs', 'file-name', true)
    ]);
    $config->setLogLevel(Logger::ERROR);

    // create a log instance
    $logger = new LogVice\PHPLogger\Logger($config);

    $logger->handleShutdownError();
});
```

## About

### Requirements

- PHPLogger works with PHP 5.4 or above..

### Submitting bugs and feature requests

Bugs and feature request use [GitHub](https://github.com/logvice/phplogger/issues)

### Author

Alban Kora - <ankdeveloper@gmail.com> - <http://twitter.com/albankora>

### License

PHPLogger is licensed under the MIT License - see the `LICENSE` file for details