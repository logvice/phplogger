<?php namespace LogVice\PHPLogger;

/*
 * This file is part of \LogVice\PHPLogger package.
 *
 * (c) Alban Kora <ankdeveloper@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

class BacktraceTest extends \PHPUnit_Framework_TestCase
{
    protected $filePath;
    protected $backtrace;

    protected function setUp()
    {
        $this->backtrace = new Backtrace(true);
    }

    public function testInactiveBacktrace()
    {
        $this->backtrace = new Backtrace(false);
        $data = $this->backtrace->info();
        $this->assertEquals('', $data);
    }

    public function testInfoEmptyBacktrace()
    {
        $this->backtrace->setTraces([]);
        $data = $this->backtrace->info();
        $this->assertEquals('[]', $data);
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

        $this->assertEquals(json_encode($expected), $data);
    }
}
