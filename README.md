# LogVice\PHPLogger - PHP Logging made easy.

[![Build Status](https://img.shields.io/travis/logvice/phplogger.svg)](https://travis-ci.org/logvice/phplogger)

LogVice\PHPLogger sends your logs to LogVice.com service or to your own LogVice platform installation. This library implements the [PSR-3](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md) standards.

## Installation

Install the latest version with

```bash
$ composer require logvice/phplogger
```

## Basic Usage

```php
<?php

use LogVice\PHPLogger\Logger;

// create a log channel
$log = new Logger('name');
$log->setOutputs(new FileOutput('path/to/your/log/directory', 'log'));

// add records to the log file
$log->warning('Foo');
$log->error('Bar');
```

## About

### Requirements

- PHPLogger works with PHP 5.4 or above..

### Submitting bugs and feature requests

Bugs and feature request are tracked on [GitHub](https://github.com/logvice/phplogger/issues)

### Author

Alban Nikolaos Kora - <ankdeveloper@gmail.com> - <http://twitter.com/albankora>

### License

PHPLogger is licensed under the MIT License - see the `LICENSE.txt` file for details