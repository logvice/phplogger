<?php

namespace LogVice\PHPLogger\Information;

use LogVice\PHPLogger\Contracts\InformationInterface;

abstract class AbstractInformation implements InformationInterface
{
    /**
     * @return string
     */
    public function getClassName()
    {
        $path = explode('\\', get_class($this));
        return array_pop($path);
    }
}
