<?php

namespace LogVice\PHPLogger\Output;

class TCPOutputTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Exception
     */
    public function testExceptionIsThrown()
    {
        $tcpOutput = new TCPOutput('127.0.0.1');
        $this->assertTrue($tcpOutput->send('test'));
    }

    /**
     * @expectedException Exception
     */
    public function testExceptionIsThrownWithEmptyString()
    {
        $tcpOutput = new TCPOutput('');
        $tcpOutput->send('test');
    }
}
