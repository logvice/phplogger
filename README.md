# LogVice\PHPLogger - PHP Logging made easy.

[![Author](http://img.shields.io/badge/author-@albankora-blue.svg?style=flat-square)](https://twitter.com/albankora)
[![Build Status](https://img.shields.io/travis/logvice/phplogger.svg)](https://travis-ci.org/logvice/phplogger)
[![Total Downloads](https://img.shields.io/packagist/dt/logvice/phplogger.svg?style=flat-square)](https://packagist.org/packages/logvice/phplogger)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

LogVice\PHPLogger sends your logs to LogVice.com service or to your own LogVice platform installation. This library implements the [PSR-3](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md) standards.

## Installation

Install the latest version with

```bash
{
    "require": {
        "logvice/phplogger": "dev-master"
    }
}
```

## Basic Usage

```php
<?php

use LogVice\PHPLogger\Logger;

// create a log channel
$log = new Logger('name');
$log->setOutputs(new FileOutput('path/to/your/log/directory', 'log'));

// add records to the log file
$log->debug('Foo');
$log->info('Bar');
$log->notice('Foo');
$log->warning('Bar');
$log->error('Foo');
$log->critical('Bar');
$log->alert('Foo');
$log->emergency('Bar');
$log->log(Logger::ERROR, 'Foo');
```

## About

### Requirements

- PHPLogger works with PHP 5.4 or above..

### Submitting bugs and feature requests

Bugs and feature request are tracked on [GitHub](https://github.com/logvice/phplogger/issues)

### Author

Alban Nikolaos Kora - <ankdeveloper@gmail.com> - <http://twitter.com/albankora>

### License

PHPLogger is licensed under the MIT License - see the `LICENSE` file for details