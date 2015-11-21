<?php

namespace LogVice\PHPLogger\Fixtures;

use LogVice\PHPLogger\Contracts\OutputInterface;

class FakeOutput implements OutputInterface
{
    /**
     * Writes a message
     *
     * @param mixed $logData
     *
     * @return mixed
     */
    public function send($logData)
    {
        return null;
    }
}
