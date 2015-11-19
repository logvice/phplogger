<?php
/**
 * Created by PhpStorm.
 * User: nikos
 * Date: 11/14/2015
 * Time: 4:09 PM
 */

namespace LogVice\PHPLogger\Output;


class TCPOutputTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException Exception
     */
    public function testExceptionIsThrown()
    {
        $tcpOutput = new TCPOutput('127.0.0.1');
        $tcpOutput->send('test');
    }
}
