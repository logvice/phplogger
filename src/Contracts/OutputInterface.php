<?php

/*
 * This file is part of \LogVice\PHPLogger package.
 *
 * (c) Alban Nikolaos Kora <ankdeveloper@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace LogVice\PHPLogger\Contracts;

interface OutputInterface
{
    /**
     * Writes a message
     *
     * @param mixed $logData
     *
     * @return mixed
     */
    public function send($logData);
}
