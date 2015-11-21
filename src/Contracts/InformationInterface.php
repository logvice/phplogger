<?php

namespace LogVice\PHPLogger\Contracts;

interface InformationInterface
{
    /**
     * Return the information
     *
     * @return array
     */
    public function info();

    public function make();
}
