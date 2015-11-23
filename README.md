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
use LogVice\PHPLogger\Output\FileOutput;

// create a log instance
$log = new Logger('channel');

//pass an object or an array of objects for multiple outputs
$log->setOutputs(new FileOutput('path/to/your/log/directory', 'log'));

// add records to the log file
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
    $logger = new Core\Adapters\LoggerAdapter('main');

    $logger->setOutputs(
        new LogVice\PHPLogger\Output\FileOutput('path/to/logs', 'error', true)
    );

    $logger->handleError($errno, $errstr, $errfile, $errline);
});

set_exception_handler(function ($exception) {
    $logger = new Core\Adapters\LoggerAdapter('main');

    $logger->setOutputs(
        new LogVice\PHPLogger\Output\FileOutput('path/to/logs', 'exception', true)
    );

    $logger->handleException($exception);
});

register_shutdown_function(function () {
    $logger = new Core\Adapters\LoggerAdapter('main');

    $logger->setOutputs(
        new LogVice\PHPLogger\Output\FileOutput('path/to/logs', 'shutdown', true)
    );

    $logger->handleShutdownError();
});
```

## About

### Requirements

- PHPLogger works with PHP 5.4 or above..

### Submitting bugs and feature requests

Bugs and feature request use [GitHub](https://github.com/logvice/phplogger/issues)

### Author

Alban Nikolaos Kora - <ankdeveloper@gmail.com> - <http://twitter.com/albankora>

### License

PHPLogger is licensed under the MIT License - see the `LICENSE` file for details