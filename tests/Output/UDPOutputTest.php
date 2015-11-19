<?php

/*
 * This file is part of \LogVice\PHPLogger package.
 *
 * (c) Alban Nikolaos Kora <ankdeveloper@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace LogVice\PHPLogger\Output;

class UDPOutputTest extends \PHPUnit_Framework_TestCase
{
    public function testUDPOutputSend()
    {
        $socket = new UDPOutput('127.0.0.1');
        $response = $socket->send('test');
        $this->assertTrue($response);
    }

    public function testDifferentPortFromDefault()
    {
        $socket = new UDPOutput('127.0.0.1', 1000);
        $response = $socket->send('test');
        $this->assertTrue($response);
    }
}
