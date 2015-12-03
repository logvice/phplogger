<?php

namespace LogVice\PHPLogger\Output;

interface OutputContract
{
    /**
     * Writes a message
     *
     * @param mixed $logData
     *
     * @return mixed
     */
    public function send($logData);
}
