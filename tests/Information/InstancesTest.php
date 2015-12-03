<?php

namespace LogVice\PHPLogger\Information;

class InstancesTest extends \PHPUnit_Framework_TestCase
{
    protected $instances;

    protected function setUp()
    {
        $this->instances = new Instances();
    }

    public function testClassName()
    {
        $this->assertEquals('Instances', $this->instances->getClassName());
    }

    public function testInfoFunction()
    {
        $info = $this->instances->info();

        $this->assertArrayHasKey('functions', $info);
        $this->assertArrayHasKey('classes', $info);
        $this->assertArrayHasKey('interfaces', $info);

        $this->assertNotEmpty($info['functions']);
        $this->assertNotEmpty($info['classes']);
        $this->assertNotEmpty($info['interfaces']);
    }
}
