<?php

/*
 * This file is part of \LogVice\PHPLogger package.
 *
 * (c) Alban Nikolaos Kora <ankdeveloper@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace LogVice\PHPLogger\Output;

use LogVice\PHPLogger\Contracts\OutputInterface;

class FileOutput implements OutputInterface
{

    protected $path = '';
    /**
     * Default filename
     */
    const FILE_NAME = 'main';

    /**
     * @var boolean|resource Opened file
     */
    protected $file = false;

    /**
     * Construct
     *
     * @param string $dir The path to the logs directory
     * @param string $name Filename
     *
     * @param bool $addDate
     */
    public function __construct($dir, $name = self::FILE_NAME, $addDate = false)
    {
        $this->path = rtrim($dir, '\\/');
        if (!file_exists($this->path)) {
            if (!mkdir($this->path, 0777)) {
                throw new \RuntimeException('Unable to create log directory.');
            }
        }

        $date = $addDate !== false ? date('_d_m_Y') : '';

        $this->path = $this->path . DIRECTORY_SEPARATOR . $name . $date . '.log';
    }

    /**
     * Writes a message to file
     *
     * @param array $logData
     * @return void
     */
    public function send($logData)
    {
        $this->open();
        $this->write($logData);
        $this->close();
    }

    /**
     * Open file or throw exception
     *
     * @throw RuntimeException
     */
    protected function open()
    {
        $this->file = fopen($this->path, 'a');

        if ($this->file === false) {
            throw new \RuntimeException('Unable to open file.');
        }
    }

    /**
     * @param string $logData Json Encoded
     * @throws \RuntimeException Unable to write message
     */
    protected function write($logData)
    {
        $logData = json_decode($logData);

        $hasBeenWritten = fwrite(
            $this->file,
            sprintf(
                '[%s] %s: %s%s%s',
                $logData->datetime,
                $logData->level_name,
                $logData->message,
                empty($logData->context) ? '' : ' ' . json_encode($logData->context),
                PHP_EOL
            )
        );

        if ($hasBeenWritten === false) {
            throw new \RuntimeException('Unable to write to file.');
        }
    }

    /**
     * Cleanup
     */
    protected function close()
    {
        if ($this->file) {
            fclose($this->file);
            $this->file = false;
        }
    }
}
