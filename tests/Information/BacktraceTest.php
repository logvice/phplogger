<?php

/*
 * This file is part of \LogVice\PHPLogger package.
 *
 * (c) Alban Nikolaos Kora <ankdeveloper@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace LogVice\PHPLogger\Information;

class BacktraceTest extends \PHPUnit_Framework_TestCase
{
    protected $filePath;
    protected $backtrace;

    protected function setUp()
    {
        $this->filePath = __DIR__ . '/../Fixtures/';
        $this->backtrace = new Backtrace();
    }

    public function testClassName()
    {
        $this->assertEquals('Backtrace', $this->backtrace->getClassName());
    }

    public function testInfoEmptyBacktrace()
    {
        $data = $this->backtrace->info();

        $expected = ['backtrace' => array(
            array(
                'line' => null,
                'function' => null,
                'file' => null,
                'class' => null
            )
        )];

        $this->assertEquals($expected, $data);
    }

    public function testBacktraceErrorHandler()
    {
        $backtraces = $this->getJson('backtraces/error_handler.json');
        $stacktrace = $this->backtrace->handle($backtraces);
        $this->assertCount(7, $stacktrace);

        $this->assertFrameEquals($stacktrace[0], null, "/Users/nick/src/Logvice/Logvice-php/testing.php", 22);
        $this->assertFrameEquals($stacktrace[1], "generate", "/Users/nick/src/Logvice/Logvice-php/src/Logvice/Error.php", 116);
        $this->assertFrameEquals($stacktrace[2], "setPHPError", "/Users/nick/src/Logvice/Logvice-php/src/Logvice/Error.php", 25);
    }

    public function testBacktraceExceptionHandler()
    {
        $backtraces = $this->getJson('backtraces/exception_handler.json');
        $stacktrace = $this->backtrace->handle($backtraces);

        $this->assertCount(3, $stacktrace);

        $this->assertFrameEquals($stacktrace[0], null, "/Users/nick/src/Logvice/Logvice-php/testing.php", 25);
        $this->assertFrameEquals($stacktrace[1], "crashy_function", "/Users/nick/src/Logvice/Logvice-php/testing.php", 13);
        $this->assertFrameEquals($stacktrace[2], "parent_of_crashy_function", "/Users/nick/src/Logvice/Logvice-php/testing.php", 28);
    }

    public function testBacktraceXDebugError()
    {
        $backtraces = $this->getJson('backtraces/xdebug_error.json');
        $stacktrace = $this->backtrace->handle($backtraces);

        $this->assertCount(7, $stacktrace);

        $this->assertFrameEquals($stacktrace[0], null, "somefile.php", 123);
        $this->assertFrameEquals($stacktrace[2], "evaluatePath", "View/Engines/CompilerEngine.php", 57);
        $this->assertFrameEquals($stacktrace[3], "get", "View/View.php", 136);
        $this->assertFrameEquals($stacktrace[4], "getContents", "View/View.php", 104);
        $this->assertFrameEquals($stacktrace[5], "renderContents", "View/View.php", 78);
    }

    public function testExceptionHandler()
    {
        $backtraces = $this->getJson('backtraces/exception_handler.json');
        $stacktrace = $this->backtrace->handle($backtraces);

        $this->assertCount(3, $stacktrace);

        $this->assertFrameEquals($stacktrace[0], null, "/Users/nick/src/Logvice/Logvice-php/testing.php", 25);
        $this->assertFrameEquals($stacktrace[1], "crashy_function", "/Users/nick/src/Logvice/Logvice-php/testing.php", 13);
        $this->assertFrameEquals($stacktrace[2], "parent_of_crashy_function", "/Users/nick/src/Logvice/Logvice-php/testing.php", 28);
    }

    protected function assertFrameEquals($frame, $method, $file, $line)
    {
        $this->assertEquals($frame["function"], $method);
        $this->assertEquals($frame["file"], $file);
        $this->assertEquals($frame["line"], $line);
    }

    protected function getJson($file)
    {
        return json_decode(file_get_contents($this->filePath . $file), true);
    }
}
