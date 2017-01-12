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
        $this->config->setAppKey('a02206e4fb278e5e80c68eb51293156a30c2a90a')
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

        $this->config->setOutputHandlers([new FakeOutput(), new UDPOutput('logvice.com')]);
        $this->assertEquals(2, count($this->config->getOutputHandlers()));

        $this->config->setOutputHandlers([new FakeOutput(), new UDPOutput('logvice.com'), new TCPOutput('logvice.com')]);
        $this->assertEquals(3, count($this->config->getOutputHandlers()));
    }

    public function testSetAppKey()
    {
        $this->assertEquals('a02206e4fb278e5e80c68eb51293156a30c2a90a', $this->config->getAppKey());

        $this->config->setAppKey('768117a946014dbf2dc19e1a0a1f707126a6f7f1');

        $this->assertEquals('768117a946014dbf2dc19e1a0a1f707126a6f7f1', $this->config->getAppKey());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidTypeAppKeyProvided()
    {
        $this->config->setAppKey(1);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidFormatAppKeyProvided()
    {
        $this->config->setAppKey('test');
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

    public function testServerValues()
    {
        $_SERVER['SERVER_ADDR'] = 'foo';

        $this->config->setServerValues(['SERVER_ADDR']);

        $values = $this->config->getServerValues();

        $this->assertArrayHasKey('SERVER_ADDR', $values);
        $this->assertEquals('foo', $values['SERVER_ADDR']);
    }

    public function testHostname()
    {
        $hostname = $this->config->getHostname();
        $this->assertTrue(is_string($hostname));
    }
}
