<?php

namespace LogVice\PHPLogger;

class RegistryTest extends \PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        Registry::clear();
        Registry::addLogger(new Logger('test', 'test'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLoggerAddAlreadyExist()
    {
        Registry::addLogger(new Logger('test', 'test'));
    }

    public function testStaticCall()
    {
        $this->assertInstanceOf('LogVice\PHPLogger\Logger', Registry::test());
    }

    public function testAddLogger()
    {
        Registry::addLogger(new Logger('test1'));
        $this->assertInstanceOf('LogVice\PHPLogger\Logger', Registry::getInstance('test1'));
    }

    public function testRegistryHasLogger()
    {
        $this->assertTrue(Registry::hasLogger('test'));
        $this->assertTrue(Registry::hasLogger(Registry::test()));
    }

    public function testRemoveLoggerUsingKey()
    {
        $this->assertTrue(Registry::hasLogger('test'));
        Registry::removeLogger('test');
        $this->assertFalse(Registry::hasLogger('test'));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testRemoveLoggerUsingObject()
    {
        $this->assertTrue(Registry::hasLogger(Registry::test()));
        Registry::removeLogger(Registry::test());
        $this->assertFalse(Registry::hasLogger(Registry::test()));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testRemoveLoggerThroughInvalidArgumentExceptionUsingStaticCall()
    {
        Registry::removeLogger(Registry::test1());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testRemoveLoggerThroughInvalidArgumentExceptionUsingString()
    {
        Registry::removeLogger('test1');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testRemoveLoggerAndThroughException()
    {
        Registry::removeLogger('test');
        Registry::getInstance('test');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testClearLogger()
    {
        Registry::clear();
        Registry::getInstance('test');
    }
}