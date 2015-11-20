<?php

namespace LogVice\PHPLogger\Contracts;

interface OutputInterface
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
