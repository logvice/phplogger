<?php namespace LogVice\PHPLogger;

/*
 * This file is part of \LogVice\PHPLogger package.
 *
 * (c) Alban Kora <ankdeveloper@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

use Psr\Log\LogLevel;
use LogVice\PHPLogger\Fixtures\FakeOutput;
use LogVice\PHPLogger\Fixtures\FakeException;

class LoggerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Logger $logger
     */
    private $logger;

    /**
     * @var Config
     */
    private $config;

    protected function setUp()
    {
        $this->config = new Config();
        $this->config->setAppKey('a02206e4fb278e5e80c68eb51293156a30c2a90a')
            ->setEnvironment('DEV')
            ->setChannel('php-test')
            ->setSessionValues(['foo'])
            ->setOutputHandlers([new FakeOutput()])
            ->setLogLevel(Logger::DEBUG)
            ->activateBacktrace()
            ->setRequestValues(['REQUEST_URI']);

        $this->logger = new Logger($this->config);
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
        $this->assertEquals('debug', $this->logger->getLogLevelName(Logger::DEBUG));
        $this->assertEquals('info', $this->logger->getLogLevelName(Logger::INFO));
        $this->assertEquals('notice', $this->logger->getLogLevelName(Logger::NOTICE));
        $this->assertEquals('warning', $this->logger->getLogLevelName(Logger::WARNING));
        $this->assertEquals('error', $this->logger->getLogLevelName(Logger::ERROR));
        $this->assertEquals('critical', $this->logger->getLogLevelName(Logger::CRITICAL));
        $this->assertEquals('alert', $this->logger->getLogLevelName(Logger::ALERT));
        $this->assertEquals('emergency', $this->logger->getLogLevelName(Logger::EMERGENCY));

        $this->assertEquals(LogLevel::DEBUG, $this->logger->getLogLevelName(Logger::DEBUG));
        $this->assertEquals(LogLevel::INFO, $this->logger->getLogLevelName(Logger::INFO));
        $this->assertEquals(LogLevel::NOTICE, $this->logger->getLogLevelName(Logger::NOTICE));
        $this->assertEquals(LogLevel::WARNING, $this->logger->getLogLevelName(Logger::WARNING));
        $this->assertEquals(LogLevel::ERROR, $this->logger->getLogLevelName(Logger::ERROR));
        $this->assertEquals(LogLevel::CRITICAL, $this->logger->getLogLevelName(Logger::CRITICAL));
        $this->assertEquals(LogLevel::ALERT, $this->logger->getLogLevelName(Logger::ALERT));
        $this->assertEquals(LogLevel::EMERGENCY, $this->logger->getLogLevelName(Logger::EMERGENCY));
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
        $this->logger->debug('test', ['test']);
        $data = $this->logger->getLogData();
        $this->assertArrayHasKey('trace', $data);
    }


    public function testDebug()
    {

        $this->logger->debug('test', ['test']);
        $data = $this->logger->getLogData();

        $this->assertArrayHasKey('app_key', $data);
        $this->assertEquals($data['app_key'], 'a02206e4fb278e5e80c68eb51293156a30c2a90a');
        $this->assertArrayHasKey('channel', $data);
        $this->assertEquals($data['channel'], 'php-test');
        $this->assertArrayHasKey('environment', $data);
        $this->assertEquals($data['environment'], 'DEV');
        $this->assertArrayHasKey('message', $data);
        $this->assertEquals($data['message'], 'test');
        $this->assertArrayHasKey('context', $data);
        $this->assertEquals($data['context'],  json_encode(['test']));
        $this->assertArrayHasKey('log_level', $data);
        $this->assertEquals($data['log_level'], Logger::DEBUG);
        $this->assertArrayHasKey('log_level_name', $data);
        $this->assertEquals($data['log_level_name'], $this->logger->getLogLevelName(Logger::DEBUG));
        $this->assertArrayHasKey('extras', $data);
        $this->assertArrayHasKey('created_at', $data);
    }

    public function testInfo()
    {
        $this->logger->info('test', ['test']);
        $data = $this->logger->getLogData();

        $this->assertArrayHasKey('app_key', $data);
        $this->assertEquals($data['app_key'], 'a02206e4fb278e5e80c68eb51293156a30c2a90a');
        $this->assertArrayHasKey('channel', $data);
        $this->assertEquals($data['channel'], 'php-test');
        $this->assertArrayHasKey('environment', $data);
        $this->assertEquals($data['environment'], 'DEV');
        $this->assertArrayHasKey('message', $data);
        $this->assertEquals($data['message'], 'test');
        $this->assertArrayHasKey('context', $data);
        $this->assertEquals($data['context'],  json_encode(['test']));
        $this->assertArrayHasKey('log_level', $data);
        $this->assertEquals($data['log_level'], Logger::INFO);
        $this->assertArrayHasKey('log_level_name', $data);
        $this->assertEquals($data['log_level_name'], $this->logger->getLogLevelName(Logger::INFO));
        $this->assertArrayHasKey('extras', $data);
        $this->assertArrayHasKey('created_at', $data);
    }

    public function testNotice()
    {
        $this->logger->notice('test', ['test']);
        $data = $this->logger->getLogData();

        $this->assertArrayHasKey('app_key', $data);
        $this->assertEquals($data['app_key'], 'a02206e4fb278e5e80c68eb51293156a30c2a90a');
        $this->assertArrayHasKey('channel', $data);
        $this->assertEquals($data['channel'], 'php-test');
        $this->assertArrayHasKey('environment', $data);
        $this->assertEquals($data['environment'], 'DEV');
        $this->assertArrayHasKey('message', $data);
        $this->assertEquals($data['message'], 'test');
        $this->assertArrayHasKey('context', $data);
        $this->assertEquals($data['context'],  json_encode(['test']));
        $this->assertArrayHasKey('log_level', $data);
        $this->assertEquals($data['log_level'], Logger::NOTICE);
        $this->assertArrayHasKey('log_level_name', $data);
        $this->assertEquals($data['log_level_name'], $this->logger->getLogLevelName(Logger::NOTICE));
        $this->assertArrayHasKey('extras', $data);
        $this->assertArrayHasKey('created_at', $data);
    }

    public function testWarning()
    {
        $this->logger->warning('test', ['test']);
        $data = $this->logger->getLogData();

        $this->assertArrayHasKey('app_key', $data);
        $this->assertEquals($data['app_key'], 'a02206e4fb278e5e80c68eb51293156a30c2a90a');
        $this->assertArrayHasKey('channel', $data);
        $this->assertEquals($data['channel'], 'php-test');
        $this->assertArrayHasKey('environment', $data);
        $this->assertEquals($data['environment'], 'DEV');
        $this->assertArrayHasKey('message', $data);
        $this->assertEquals($data['message'], 'test');
        $this->assertArrayHasKey('context', $data);
        $this->assertEquals($data['context'],  json_encode(['test']));
        $this->assertArrayHasKey('log_level', $data);
        $this->assertEquals($data['log_level'], Logger::WARNING);
        $this->assertArrayHasKey('log_level_name', $data);
        $this->assertEquals($data['log_level_name'], $this->logger->getLogLevelName(Logger::WARNING));
        $this->assertArrayHasKey('extras', $data);
        $this->assertArrayHasKey('created_at', $data);
    }

    public function testError()
    {
        $this->logger->error('test', ['test']);
        $data = $this->logger->getLogData();

        $this->assertArrayHasKey('app_key', $data);
        $this->assertEquals($data['app_key'], 'a02206e4fb278e5e80c68eb51293156a30c2a90a');
        $this->assertArrayHasKey('channel', $data);
        $this->assertEquals($data['channel'], 'php-test');
        $this->assertArrayHasKey('environment', $data);
        $this->assertEquals($data['environment'], 'DEV');
        $this->assertArrayHasKey('message', $data);
        $this->assertEquals($data['message'], 'test');
        $this->assertArrayHasKey('context', $data);
        $this->assertEquals($data['context'],  json_encode(['test']));
        $this->assertArrayHasKey('log_level', $data);
        $this->assertEquals($data['log_level'], Logger::ERROR);
        $this->assertArrayHasKey('log_level_name', $data);
        $this->assertEquals($data['log_level_name'], $this->logger->getLogLevelName(Logger::ERROR));
        $this->assertArrayHasKey('extras', $data);
        $this->assertArrayHasKey('created_at', $data);
    }

    public function testCritical()
    {
        $this->logger->critical('test', ['test']);
        $data = $this->logger->getLogData();

        $this->assertArrayHasKey('app_key', $data);
        $this->assertEquals($data['app_key'], 'a02206e4fb278e5e80c68eb51293156a30c2a90a');
        $this->assertArrayHasKey('channel', $data);
        $this->assertEquals($data['channel'], 'php-test');
        $this->assertArrayHasKey('environment', $data);
        $this->assertEquals($data['environment'], 'DEV');
        $this->assertArrayHasKey('message', $data);
        $this->assertEquals($data['message'], 'test');
        $this->assertArrayHasKey('context', $data);
        $this->assertEquals($data['context'],  json_encode(['test']));
        $this->assertArrayHasKey('log_level', $data);
        $this->assertEquals($data['log_level'], Logger::CRITICAL);
        $this->assertArrayHasKey('log_level_name', $data);
        $this->assertEquals($data['log_level_name'], $this->logger->getLogLevelName(Logger::CRITICAL));
        $this->assertArrayHasKey('extras', $data);
        $this->assertArrayHasKey('created_at', $data);
    }

    public function testAlert()
    {
        $this->logger->alert('test', ['test']);
        $data = $this->logger->getLogData();

        $this->assertArrayHasKey('app_key', $data);
        $this->assertEquals($data['app_key'], 'a02206e4fb278e5e80c68eb51293156a30c2a90a');
        $this->assertArrayHasKey('channel', $data);
        $this->assertEquals($data['channel'], 'php-test');
        $this->assertArrayHasKey('environment', $data);
        $this->assertEquals($data['environment'], 'DEV');
        $this->assertArrayHasKey('message', $data);
        $this->assertEquals($data['message'], 'test');
        $this->assertArrayHasKey('context', $data);
        $this->assertEquals($data['context'],  json_encode(['test']));
        $this->assertArrayHasKey('log_level', $data);
        $this->assertEquals($data['log_level'], Logger::ALERT);
        $this->assertArrayHasKey('log_level_name', $data);
        $this->assertEquals($data['log_level_name'], $this->logger->getLogLevelName(Logger::ALERT));
        $this->assertArrayHasKey('extras', $data);
        $this->assertArrayHasKey('created_at', $data);
    }

    public function testEmergency()
    {
        $this->logger->emergency('test', ['test']);
        $data = $this->logger->getLogData();

        $this->assertArrayHasKey('app_key', $data);
        $this->assertEquals($data['app_key'], 'a02206e4fb278e5e80c68eb51293156a30c2a90a');
        $this->assertArrayHasKey('channel', $data);
        $this->assertEquals($data['channel'], 'php-test');
        $this->assertArrayHasKey('environment', $data);
        $this->assertEquals($data['environment'], 'DEV');
        $this->assertArrayHasKey('message', $data);
        $this->assertEquals($data['message'], 'test');
        $this->assertArrayHasKey('context', $data);
        $this->assertEquals($data['context'],  json_encode(['test']));
        $this->assertArrayHasKey('log_level', $data);
        $this->assertEquals($data['log_level'], Logger::EMERGENCY);
        $this->assertArrayHasKey('log_level_name', $data);
        $this->assertEquals($data['log_level_name'], $this->logger->getLogLevelName(Logger::EMERGENCY));
        $this->assertArrayHasKey('extras', $data);
        $this->assertArrayHasKey('created_at', $data);
    }

    public function testLog()
    {
        $this->logger->log(Logger::DEBUG, 'test', ['test']);
        $data = $this->logger->getLogData();

        $this->assertArrayHasKey('app_key', $data);
        $this->assertEquals($data['app_key'], 'a02206e4fb278e5e80c68eb51293156a30c2a90a');
        $this->assertArrayHasKey('channel', $data);
        $this->assertEquals($data['channel'], 'php-test');
        $this->assertArrayHasKey('environment', $data);
        $this->assertEquals($data['environment'], 'DEV');
        $this->assertArrayHasKey('message', $data);
        $this->assertEquals($data['message'], 'test');
        $this->assertArrayHasKey('context', $data);
        $this->assertEquals($data['context'],  json_encode(['test']));
        $this->assertArrayHasKey('log_level', $data);
        $this->assertEquals($data['log_level'], Logger::DEBUG);
        $this->assertArrayHasKey('log_level_name', $data);
        $this->assertEquals($data['log_level_name'], $this->logger->getLogLevelName(Logger::DEBUG));
        $this->assertArrayHasKey('extras', $data);
        $this->assertArrayHasKey('created_at', $data);
    }

    public function testHandleErrorNotice()
    {
        $this->logger->handleError(E_USER_NOTICE, 'test', 'test', 20);
        $data = $this->logger->getLogData();

        $this->assertArrayHasKey('app_key', $data);
        $this->assertEquals($data['app_key'], 'a02206e4fb278e5e80c68eb51293156a30c2a90a');
        $this->assertArrayHasKey('channel', $data);
        $this->assertEquals($data['channel'], 'php-test');
        $this->assertArrayHasKey('environment', $data);
        $this->assertEquals($data['environment'], 'DEV');
        $this->assertArrayHasKey('message', $data);
        $this->assertEquals($data['message'], 'test');
        $this->assertArrayHasKey('context', $data);
        $this->assertEquals($data['context'], json_encode(['file' => 'test','line' => 20]));
        $this->assertArrayHasKey('log_level', $data);
        $this->assertEquals($data['log_level'], Logger::NOTICE);
        $this->assertArrayHasKey('log_level_name', $data);
        $this->assertEquals($data['log_level_name'], $this->logger->getLogLevelName(Logger::NOTICE));
        $this->assertArrayHasKey('extras', $data);
        $this->assertArrayHasKey('created_at', $data);
    }

    public function testHandleErrorWarning()
    {
        $this->logger->handleError(E_USER_WARNING, 'test', 'test', 20);
        $data = $this->logger->getLogData();

        $this->assertArrayHasKey('app_key', $data);
        $this->assertEquals($data['app_key'], 'a02206e4fb278e5e80c68eb51293156a30c2a90a');
        $this->assertArrayHasKey('channel', $data);
        $this->assertEquals($data['channel'], 'php-test');
        $this->assertArrayHasKey('environment', $data);
        $this->assertEquals($data['environment'], 'DEV');
        $this->assertArrayHasKey('message', $data);
        $this->assertEquals($data['message'], 'test');
        $this->assertArrayHasKey('context', $data);
        $this->assertEquals($data['context'],  json_encode(['file' => 'test','line' => 20]));
        $this->assertArrayHasKey('log_level', $data);
        $this->assertEquals($data['log_level'], Logger::WARNING);
        $this->assertArrayHasKey('log_level_name', $data);
        $this->assertEquals($data['log_level_name'], $this->logger->getLogLevelName(Logger::WARNING));
        $this->assertArrayHasKey('extras', $data);
        $this->assertArrayHasKey('created_at', $data);
    }

    public function testHandleError()
    {
        $this->logger->handleError(E_USER_ERROR, 'test1', 'test1', 10);
        $data = $this->logger->getLogData();

        $this->assertArrayHasKey('app_key', $data);
        $this->assertEquals($data['app_key'], 'a02206e4fb278e5e80c68eb51293156a30c2a90a');
        $this->assertArrayHasKey('channel', $data);
        $this->assertEquals($data['channel'], 'php-test');
        $this->assertArrayHasKey('environment', $data);
        $this->assertEquals($data['environment'], 'DEV');
        $this->assertArrayHasKey('message', $data);
        $this->assertEquals($data['message'], 'test1');
        $this->assertArrayHasKey('context', $data);
        $this->assertEquals($data['context'],  json_encode(['file' => 'test1','line' => 10]));
        $this->assertArrayHasKey('log_level', $data);
        $this->assertEquals($data['log_level'], Logger::ERROR);
        $this->assertArrayHasKey('log_level_name', $data);
        $this->assertEquals($data['log_level_name'], $this->logger->getLogLevelName(Logger::ERROR));
        $this->assertArrayHasKey('extras', $data);
        $this->assertArrayHasKey('created_at', $data);
    }

    public function testException()
    {
        $exception = new FakeException();
        $exception->setMessage('test');
        $exception->setCode(200);
        $exception->setFile('test');
        $exception->setLine(100);

        $this->logger->handleException($exception);
        $data = $this->logger->getLogData();

        $this->assertArrayHasKey('app_key', $data);
        $this->assertEquals($data['app_key'], 'a02206e4fb278e5e80c68eb51293156a30c2a90a');
        $this->assertArrayHasKey('channel', $data);
        $this->assertEquals($data['channel'], 'php-test');
        $this->assertArrayHasKey('environment', $data);
        $this->assertEquals($data['environment'], 'DEV');
        $this->assertArrayHasKey('message', $data);
        $this->assertEquals($data['message'], 'test');
        $this->assertArrayHasKey('context', $data);
        $this->assertEquals($data['context'],  json_encode(['file' => 'test','line' => 100]));
        $this->assertArrayHasKey('log_level', $data);
        $this->assertEquals($data['log_level'], Logger::ERROR);
        $this->assertArrayHasKey('log_level_name', $data);
        $this->assertEquals($data['log_level_name'], $this->logger->getLogLevelName(Logger::ERROR));
        $this->assertArrayHasKey('extras', $data);
        $this->assertArrayHasKey('created_at', $data);
    }

    public function testOutputLevelIsLargerThanSubmittedOneAndReturnFalse()
    {
        $config = new Config();
        $config->setAppKey('a02206e4fb278e5e80c68eb51293156a30c2a90a')
            ->setLogLevel(Logger::ERROR);

        $logger = new Logger($config);
        $result = $logger->info('test', ['test']);
        $this->assertFalse($result);
    }

    public function testHandleShutdownErrorCall()
    {
        $result = $this->logger->handleShutdown();
        $this->assertFalse($result);
    }

    public function testConvertError()
    {
        $this->assertEquals(Logger::CRITICAL, $this->logger->convertErrorLevel(E_ERROR));
        $this->assertEquals(Logger::ERROR, $this->logger->convertErrorLevel(E_USER_ERROR));
        $this->assertEquals(Logger::WARNING, $this->logger->convertErrorLevel(E_WARNING));
        $this->assertEquals(Logger::ALERT, $this->logger->convertErrorLevel(E_PARSE));
        $this->assertEquals(Logger::NOTICE, $this->logger->convertErrorLevel(E_NOTICE));
        $this->assertEquals(Logger::NOTICE, $this->logger->convertErrorLevel(E_DEPRECATED));
    }
}
