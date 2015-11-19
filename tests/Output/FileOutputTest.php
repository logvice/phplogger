<?php
/**
 * Created by PhpStorm.
 * User: nikos
 * Date: 11/14/2015
 * Time: 4:10 PM
 */

namespace LogVice\PHPLogger\Output;


class FileOutputTest extends \PHPUnit_Framework_TestCase
{
    protected $fileOutput;
    protected $dir;

    protected function setUp()
    {
        $this->dir = __DIR__ . '/../Fixtures/logs/';
        $this->fileOutput = new FileOutput($this->dir, 'log', true);
    }

    public function testDirectoryHasBeenCreated()
    {
        $this->assertTrue(file_exists($this->dir));
    }

    public function testFileHasBeenCreated()
    {
        $logData = json_encode(['level_name' => 'ERROR', 'message' => 'test', 'context' => 'test', 'datetime' => date('d/m/Y h:m:s')]);
        $this->fileOutput->send($logData);
        $this->assertTrue(file_exists($this->dir . 'log' . date('_d_m_Y') . '.log'));
    }

    public function testLogFileHasContent()
    {
        $logData = json_encode(['level_name' => 'ERROR', 'message' => 'test', 'context' => 'test', 'datetime' => date('d/m/Y h:m:s')]);
        $this->fileOutput->send($logData);
        $this->assertEquals(1, $this->fileLineCount());
        $this->fileOutput->send($logData);
        $this->assertEquals(2, $this->fileLineCount());
    }

    protected function fileLineCount()
    {
        $f = fopen($this->dir . 'log' . date('_d_m_Y') . '.log', "r", 'rb');
        $lines = 0;

        while (!feof($f)) {
            $lines += substr_count(fread($f, 8192), "\n");
        }
        fclose($f);

        return $lines;
    }

    protected function tearDown()
    {
        array_map('unlink', glob("$this->dir/*.*"));
        rmdir($this->dir);
    }
}
