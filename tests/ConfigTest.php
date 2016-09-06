<?php namespace LogVice\PHPLogger;

/*
 * This file is part of \LogVice\PHPLogger package.
 *
 * (c) Alban Kora <ankdeveloper@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

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
            ->setCollectorUrl('127.0.0.1')
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
    }
}
