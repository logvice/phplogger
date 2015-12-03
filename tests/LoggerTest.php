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
use LogVice\PHPLogger\Information\Instances;
use LogVice\PHPLogger\Information\Request;
use LogVice\PHPLogger\Output\FileOutput;
use LogVice\PHPLogger\Output\TCPOutput;
use LogVice\PHPLogger\Output\UDPOutput;

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
        $this->assertEquals('DEBUG', $this->logger->getLogLevelName(Logger::DEBUG));
        $this->assertEquals('INFO', $this->logger->getLogLevelName(Logger::INFO));
        $this->assertEquals('NOTICE', $this->logger->getLogLevelName(Logger::NOTICE));
        $this->assertEquals('WARNING', $this->logger->getLogLevelName(Logger::WARNING));
        $this->assertEquals('ERROR', $this->logger->getLogLevelName(Logger::ERROR));
        $this->assertEquals('CRITICAL', $this->logger->getLogLevelName(Logger::CRITICAL));
        $this->assertEquals('ALERT', $this->logger->getLogLevelName(Logger::ALERT));
        $this->assertEquals('EMERGENCY', $this->logger->getLogLevelName(Logger::EMERGENCY));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidErrorLogName()
    {
        $this->logger->getLogLevelName('test');
    }

    public function testBacktraceHasBeenAdded()
    {
        $this->logger->withBacktrace(true);
        $this->logger->debug('test', ['test']);
        $data = $this->logger->getLogData();
        $this->assertArrayHasKey('Backtrace', $data['extra']);
    }


    public function testDebug()
    {
        $this->logger->debug('test', ['test']);
        $expected = [
            'appId' => '',
            'channel' => 'test',
            'message' => 'test',
            'context' => ['test'],
            'log_level' => 100,
            'log_level_name' => 'DEBUG',
            'datetime' => $this->logger->getTimeFormatted(),
            'user' => null
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
            'context' => ['test'],
            'log_level' => 200,
            'log_level_name' => 'INFO',
            'datetime' => $this->logger->getTimeFormatted(),
            'user' => null
        ];
        $this->assertEquals($expected, $this->logger->getLogData());
    }

    public function testInfoWithAppId()
    {
        $this->logger->setAppId('test');
        $this->logger->info('test', ['test']);
        $this->assertEquals('test', $this->logger->getLogData()['appId']);
    }

    public function testNotice()
    {
        $this->logger->notice('test', ['test']);
        $expected = [
            'appId' => '',
            'channel' => 'test',
            'message' => 'test',
            'context' => ['test'],
            'log_level' => 250,
            'log_level_name' => 'NOTICE',
            'datetime' => $this->logger->getTimeFormatted(),
            'user' => null
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
            'context' => ['test'],
            'log_level' => 300,
            'log_level_name' => 'WARNING',
            'datetime' => $this->logger->getTimeFormatted(),
            'user' => null
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
            'context' => ['test'],
            'log_level' => 400,
            'log_level_name' => 'ERROR',
            'datetime' => $this->logger->getTimeFormatted(),
            'user' => null
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
            'context' => ['test'],
            'log_level' => 500,
            'log_level_name' => 'CRITICAL',
            'datetime' => $this->logger->getTimeFormatted(),
            'user' => null
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
            'context' => ['test'],
            'log_level' => 550,
            'log_level_name' => 'ALERT',
            'datetime' => $this->logger->getTimeFormatted(),
            'user' => null
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
            'context' => ['test'],
            'log_level' => 600,
            'log_level_name' => 'EMERGENCY',
            'datetime' => $this->logger->getTimeFormatted(),
            'user' => null
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
            'context' => ['test'],
            'log_level' => 100,
            'log_level_name' => 'DEBUG',
            'datetime' => $this->logger->getTimeFormatted(),
            'user' => null
        ];
        $this->assertEquals($expected, $this->logger->getLogData());
    }

    public function testTimeFormatted()
    {
        $this->logger->log(100, 'test', ['test']);
        $timeFormatted = $this->logger->getTimeFormatted();
        $this->assertEquals($timeFormatted, date('Y-m-d H:i:s', strtotime($timeFormatted)));
    }

    public function testHandleErrorNotice()
    {
        $this->logger->handleError(E_USER_NOTICE, 'test', 'test', 20);

        $expected = [
            'appId' => '',
            'channel' => 'test',
            'message' => 'test',
            'context' => [
                'file' => 'test',
                'line' => 20
            ],
            'log_level' => 250,
            'log_level_name' => 'NOTICE',
            'datetime' => $this->logger->getTimeFormatted(),
            'user' => null
        ];

        $this->assertEquals($expected, $this->logger->getLogData());
    }

    public function testHandleErrorWarning()
    {
        $this->logger->handleError(E_USER_WARNING, 'test', 'test', 20);

        $expected = [
            'appId' => '',
            'channel' => 'test',
            'message' => 'test',
            'context' => [
                'file' => 'test',
                'line' => 20
            ],
            'log_level' => 300,
            'log_level_name' => 'WARNING',
            'datetime' => $this->logger->getTimeFormatted(),
            'user' => null
        ];

        $this->assertEquals($expected, $this->logger->getLogData());
    }

    public function testHandleError()
    {
        $this->logger->handleError(E_USER_ERROR, 'test1', 'test1', 10);

        $expected = [
            'appId' => '',
            'channel' => 'test',
            'message' => 'test1',
            'context' => [
                'file' => 'test1',
                'line' => 10
            ],
            'log_level' => 400,
            'log_level_name' => 'ERROR',
            'datetime' => $this->logger->getTimeFormatted(),
            'user' => null
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
            'context' => [
                'file' => 'test',
                'line' => 100,
            ],
            'log_level' => 400,
            'log_level_name' => 'ERROR',
            'datetime' => $this->logger->getTimeFormatted(),
            'user' => null
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

    public function testOutputWasSet()
    {
        $this->logger->setOutputs(new FakeOutput());
        $this->assertEquals(1, count($this->logger->getOutputs()));
    }

    public function testOutputsWereAdded()
    {
        $this->logger->setOutputs([new FakeOutput()]);
        $this->logger->addOutputs([new UDPOutput('127.0.0.1')]);
        $this->logger->addOutputs(new TCPOutput('127.0.0.1'));
        $this->logger->addOutputs(new FileOutput(__DIR__ . '/Fixtures/logs/'));

        $this->assertEquals(4, count($this->logger->getOutputs()));
    }

    public function testInformationWasSet()
    {
        $this->logger->setInformation([new FakeInformation()]);
        $this->logger->log(100, 'test', ['test']);
        $data = $this->logger->getLogData();
        $this->assertArrayHasKey('FakeInformation', $data['extra']);
    }

    public function testAddAdditionalInformation()
    {
        $this->logger->setInformation([new FakeInformation()]);
        $this->logger->addInformation([new Request()]);
        $this->logger->addInformation(new Instances());

        $this->logger->log(100, 'test', ['test']);
        $data = $this->logger->getLogData();

        $this->assertArrayHasKey('FakeInformation', $data['extra']);
        $this->assertArrayHasKey('Request', $data['extra']);
        $this->assertArrayHasKey('Instances', $data['extra']);
    }

    public function testHandleShutdownErrorCall()
    {
        $result = $this->logger->handleShutdown();
        $this->assertFalse($result);
    }
}
