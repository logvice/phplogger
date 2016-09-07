<?php namespace LogVice\PHPLogger;

/*
 * This file is part of \LogVice\PHPLogger package.
 *
 * (c) Alban Kora <ankdeveloper@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

use LogVice\PHPLogger\Output\TCPOutput;
use LogVice\PHPLogger\Output\UDPOutput;
use LogVice\PHPLogger\Fixtures\FakeOutput;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Config
     */
    private $config;

    public function setUp()
    {
        $this->config = new Config();
        $this->config->setAppId('b85066fc-248f-4ea9-b13d-0858dbf4efc1')
            ->setEnvironment('DEV')
            ->setChannel('php-test')
            ->setSessionValues(['user'])
            ->setOutputHandlers([new FakeOutput()])
            ->setLogLevel(Logger::DEBUG)
            ->setRequestValues(['REQUEST_URI']);
    }

    public function testChannelName()
    {
        $this->assertEquals('php-test', $this->config->getChannel());
    }


    public function testTimeFormatted()
    {
        $timeFormatted = $this->config->getDateTimeFormatted();
        $this->assertEquals($timeFormatted, date('Y-m-d H:i:s', strtotime($timeFormatted)));
    }


    public function testOutputWasSet()
    {
        $this->config->setOutputHandlers([new FakeOutput()]);
        $this->assertEquals(1, count($this->config->getOutputHandlers()));

        $this->config->setOutputHandlers([new FakeOutput(), new UDPOutput('test')]);
        $this->assertEquals(2, count($this->config->getOutputHandlers()));

        $this->config->setOutputHandlers([new FakeOutput(), new UDPOutput('test'), new TCPOutput('test')]);
        $this->assertEquals(3, count($this->config->getOutputHandlers()));
    }

    public function testSetAppId()
    {
        $this->assertEquals('b85066fc-248f-4ea9-b13d-0858dbf4efc1', $this->config->getAppId());

        $this->config->setAppId('a85066fc-248f-4ea9-b13d-0858dbf4efc1');

        $this->assertEquals('a85066fc-248f-4ea9-b13d-0858dbf4efc1', $this->config->getAppId());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidTypeAppIdProvided()
    {
        $this->config->setAppId(1);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidFormatAppIdProvided()
    {
        $this->config->setAppId('test');
    }

    public function testSetEnvironment()
    {
        $this->assertEquals('DEV', $this->config->getEnvironment());

        $this->config->setEnvironment('Production');

        $this->assertEquals('Production', $this->config->getEnvironment());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidTypeEnvironment()
    {
        $this->config->setEnvironment(1);
    }


    public function testSetChannel()
    {
        $this->assertEquals('php-test', $this->config->getChannel());

        $this->config->setChannel('php-app-production');

        $this->assertEquals('php-app-production', $this->config->getChannel());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidTypeChannel()
    {
        $this->config->setChannel(1);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetWrongClassTypeOutputHandlers()
    {
        $this->config->setOutputHandlers([new \stdClass()]);
    }

    public function testRequestValues()
    {
        $_REQUEST['REQUEST_URI'] = '/foo';
        $this->config->setRequestValues(['REQUEST_URI']);

        $values = $this->config->getRequestValues();

        $this->assertArrayHasKey('REQUEST_URI', $values);
        $this->assertEquals('/foo', $values['REQUEST_URI']);
    }

    public function testSession()
    {
        $_SESSION['user'] = 'foo';

        $this->config->setSessionValues(['user']);

        $values = $this->config->getSessionValues();

        $this->assertArrayHasKey('user', $values);
        $this->assertEquals('foo', $values['user']);
    }


}
