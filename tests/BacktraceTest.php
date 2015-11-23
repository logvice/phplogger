<?php

namespace LogVice\PHPLogger;

class BacktraceTest extends \PHPUnit_Framework_TestCase
{
    protected $filePath;
    protected $backtrace;

    protected function setUp()
    {
        $this->backtrace = new Backtrace();
    }

    public function testInfoEmptyBacktrace()
    {
        $this->backtrace->setTraces([]);
        $data = $this->backtrace->info();
        $this->assertEmpty($data);
    }

    public function testBacktrace()
    {
        $traces = [
            0 => [
                "file" => "/Users/nick/src/Logvice/Logvice-php/testing.php",
                "line" => 13,
                "class" => "MyClass",
                "type" => "->",
                "function" => "crashy_function",
                "args" => "test"
            ]
        ];

        $this->backtrace->setTraces($traces);
        $data = $this->backtrace->info();

        $expected = [
            0 => [
                "file" => "/Users/nick/src/Logvice/Logvice-php/testing.php",
                "line" => 13,
                "class" => "MyClass",
                "function" => "crashy_function",
                "args" => "test"
            ]
        ];

        $this->assertEquals($expected, $data);
    }
}
