<?php namespace LogVice\PHPLogger\Output;

/*
 * This file is part of \LogVice\PHPLogger package.
 *
 * (c) Alban Kora <ankdeveloper@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

class TCPOutputTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Exception
     */
    public function testExceptionIsThrown()
    {
        $tcpOutput = new TCPOutput('127.0.0.1', 1);
        $this->assertTrue($tcpOutput->send('test'));
    }

    /**
     * @expectedException Exception
     */
    public function testExceptionIsThrownWithEmptyString()
    {
        $tcpOutput = new TCPOutput('', 1);
        $tcpOutput->send('test');
    }
}
