<?php

namespace LogVice\PHPLogger\Information;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    protected $application;

    protected function setUp()
    {
        $this->application = new Application();
    }

    public function testClassName()
    {
        $this->assertEquals('Application', $this->application->getClassName());
    }

    public function testInfoFunction()
    {

        $this->application->setUser('test');
        $expected = [
            'user' => 'test'
        ];

        $this->assertEquals($expected, $this->application->info());
    }
}
