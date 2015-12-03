<?php

namespace LogVice\PHPLogger\Fixtures;

use LogVice\PHPLogger\Output\OutputContract;

class FakeOutput implements OutputContract
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
