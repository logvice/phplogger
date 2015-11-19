<?php

/*
 * This file is part of \LogVice\PHPLogger package.
 *
 * (c) Alban Nikolaos Kora <ankdeveloper@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace LogVice\PHPLogger\Fixtures;

use LogVice\PHPLogger\Information\AbstractInformation;

class FakeInformation extends AbstractInformation {
    /**
     * Return the information
     *
     * @return array
     */
    public function info()
    {
        return '';
    }

}