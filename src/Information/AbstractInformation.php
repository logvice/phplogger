<?php

/*
 * This file is part of \LogVice\PHPLogger package.
 *
 * (c) Alban Nikolaos Kora <ankdeveloper@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace LogVice\PHPLogger\Information;

use LogVice\PHPLogger\Contracts\InformationInterface;

abstract class AbstractInformation implements InformationInterface
{
    public function getClassName()
    {
        $path = explode('\\', get_class($this));
        return array_pop($path);
    }
}