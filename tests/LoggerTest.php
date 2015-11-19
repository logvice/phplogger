<?php

/*
 * This file is part of \LogVice\PHPLogger package.
 *
 * (c) Alban Nikolaos Kora <ankdeveloper@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace LogVice\PHPLogger;

use LogVice\PHPLogger\Fixtures\FakeException;
use LogVice\PHPLogger\Fixtures\FakeInformation;
use LogVice\PHPLogger\Fixtures\FakeOutput;

class LoggerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Logger $logger
     */
    private $logger;

    protected function setUp()
    {
        $this->logger = new Logger('test', 'test');
        $this->logger->setOutputs([new FakeOutput()]);
    }

    public function testChannelName()
    {
        $this->assertEquals('test', $this->logger->getChannel());
    }

    public function testErrorIds()
    {
        $this->assertEquals(100, Logger::DEBUG);
        $this->assertEquals(200, Logger::INFO);
        $this->assertEquals(250, Logger::NOTICE);
        $this->assertEquals(300, Logger::WARNING);
        $this->assertEquals(400, Logger::ERROR);
        $this->assertEquals(500, Logger::CRITICAL);
        $this->assertEquals(550, Logger::ALERT);
        $this->assertEquals(600, Logger::EMERGENCY);
    }

    public function testErrorNames()
    {
        $this->assertEquals('DEBUG', Logger::getLevelName(Logger::DEBUG));
        $this->assertEquals('INFO', Logger::getLevelName(Logger::INFO));
        $this->assertEquals('NOTICE', Logger::getLevelName(Logger::NOTICE));
        $this->assertEquals('WARNING', Logger::getLevelName(Logger::WARNING));
        $this->assertEquals('ERROR', Logger::getLevelName(Logger::ERROR));
        $this->assertEquals('CRITICAL', Logger::getLevelName(Logger::CRITICAL));
        $this->assertEquals('ALERT', Logger::getLevelName(Logger::ALERT));
        $this->assertEquals('EMERGENCY', Logger::getLevelName(Logger::EMERGENCY));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidErrorLogName()
    {
        Logger::getLevelName('test');
    }

    public function testDebug()
    {
        $this->logger->debug('test', ['test']);
        $expected = [
            'appId' => '',
            'channel' => 'test',
            'message' => 'test',
            'context' => array('test'),
            'level' => 100,
            'level_name' => 'DEBUG',
            'datetime' => $this->logger->getTimeFormatted()
        ];
        $this->assertEquals($expected, $this->logger->getLogData());
    }

    public function testInfo()
    {
        $this->logger->info('test', ['test']);
        $expected = [
            'appId' => '',
            'channel' => 'test',
            'message' => 'test',
            'context' => array('test'),
            'level' => 200,
            'level_name' => 'INFO',
            'datetime' => $this->logger->getTimeFormatted()
        ];
        $this->assertEquals($expected, $this->logger->getLogData());
    }

    public function testNotice()
    {
        $this->logger->notice('test', ['test']);
        $expected = [
            'appId' => '',
            'channel' => 'test',
            'message' => 'test',
            'context' => array('test'),
            'level' => 250,
            'level_name' => 'NOTICE',
            'datetime' => $this->logger->getTimeFormatted()
        ];
        $this->assertEquals($expected, $this->logger->getLogData());
    }

    public function testWarning()
    {
        $this->logger->warning('test', ['test']);
        $expected = [
            'appId' => '',
            'channel' => 'test',
            'message' => 'test',
            'context' => array('test'),
            'level' => 300,
            'level_name' => 'WARNING',
            'datetime' => $this->logger->getTimeFormatted()
        ];
        $this->assertEquals($expected, $this->logger->getLogData());
    }

    public function testError()
    {
        $this->logger->error('test', ['test']);
        $expected = [
            'appId' => '',
            'channel' => 'test',
            'message' => 'test',
            'context' => array('test'),
            'level' => 400,
            'level_name' => 'ERROR',
            'datetime' => $this->logger->getTimeFormatted()
        ];
        $this->assertEquals($expected, $this->logger->getLogData());
    }

    public function testCritical()
    {
        $this->logger->critical('test', ['test']);
        $expected = [
            'appId' => '',
            'channel' => 'test',
            'message' => 'test',
            'context' => array('test'),
            'level' => 500,
            'level_name' => 'CRITICAL',
            'datetime' => $this->logger->getTimeFormatted()
        ];
        $this->assertEquals($expected, $this->logger->getLogData());
    }

    public function testAlert()
    {
        $this->logger->alert('test', ['test']);
        $expected = [
            'appId' => '',
            'channel' => 'test',
            'message' => 'test',
            'context' => array('test'),
            'level' => 550,
            'level_name' => 'ALERT',
            'datetime' => $this->logger->getTimeFormatted()
        ];
        $this->assertEquals($expected, $this->logger->getLogData());
    }

    public function testEmergency()
    {
        $this->logger->emergency('test', ['test']);
        $expected = [
            'appId' => '',
            'channel' => 'test',
            'message' => 'test',
            'context' => array('test'),
            'level' => 600,
            'level_name' => 'EMERGENCY',
            'datetime' => $this->logger->getTimeFormatted()
        ];
        $this->assertEquals($expected, $this->logger->getLogData());
    }

    public function testLog()
    {
        $this->logger->log(100, 'test', ['test']);
        $expected = [
            'appId' => '',
            'channel' => 'test',
            'message' => 'test',
            'context' => array('test'),
            'level' => 100,
            'level_name' => 'DEBUG',
            'datetime' => $this->logger->getTimeFormatted()
        ];
        $this->assertEquals($expected, $this->logger->getLogData());
    }

    public function testTimeFormatted()
    {
        $this->logger->log(100, 'test', ['test']);
        $timeFormatted = $this->logger->getTimeFormatted();
        $this->assertEquals($timeFormatted, date('Y-m-d H:i:s', strtotime($timeFormatted)));
    }

    public function testInformationAdded()
    {
        $this->logger->setInformation([new FakeInformation()]);
        $this->logger->log(100, 'test', ['test']);
        $expected = [
            'appId' => '',
            'channel' => 'test',
            'message' => 'test',
            'context' => array('test'),
            'level' => 100,
            'level_name' => 'DEBUG',
            'datetime' => $this->logger->getTimeFormatted(),
            'extra' => array('FakeInformation' => '')
        ];
        $this->assertEquals($expected, $this->logger->getLogData());
    }

    public function testHandleError()
    {
        $this->logger->handleError(200, 'test', 'test', 20);

        $expected = [
            'appId' => '',
            'channel' => 'test',
            'message' => 'test',
            'context' => array(
                'file' => 'test',
                'line' => 20
            ),
            'level' => 200,
            'level_name' => 'INFO',
            'datetime' => $this->logger->getTimeFormatted()
        ];

        $this->assertEquals($expected, $this->logger->getLogData());
    }

    public function testException()
    {
        $exception = new FakeException();
        $exception->setMessage('test');
        $exception->setCode(200);
        $exception->setFile('test');
        $exception->setLine(100);

        $this->logger->handleException($exception);

        $expected = [
            'appId' => '',
            'channel' => 'test',
            'message' => 'test',
            'context' => array(
                'file' => 'test',
                'line' => 100,
            ),
            'level' => 200,
            'level_name' => 'INFO',
            'datetime' => $this->logger->getTimeFormatted()
        ];

        $this->assertEquals($expected, $this->logger->getLogData());
    }

    public function testOutputLevelIsLargerThanSubmittedOneAndReturnFalse()
    {
        $logger = new Logger('test1', Logger::WARNING);
        $result = $logger->info('test', ['test']);
        $this->assertFalse($result);

    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testStringIsPassToOutputAndExceptionIsThrown()
    {
        $logger = new Logger('test1');
        $logger->setOutputs('test');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testStringIsPassToInformationAndExceptionIsThrown()
    {
        $logger = new Logger('test1');
        $logger->setInformation('test');
    }

    public function testObjectIsPassToOutputAndExceptionIsThrown()
    {
        $logger = new Logger('test1');
        $logger->setOutputs(new FakeOutput());

        $expected = [new FakeOutput()];
        $this->assertEquals($expected, $logger->getOutputs());
    }

    public function testObjectIsPassToInformationAndExceptionIsThrown()
    {
        $logger = new Logger('test1');
        $logger->setInformation(new FakeInformation());

        $expected = [new FakeInformation()];
        $this->assertEquals($expected, $logger->getInformation());
    }

    public function testHandleShutdownErrorCall()
    {
        $this->assertNull($this->logger->handleShutdownError());
    }
}
