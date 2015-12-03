<?php

namespace LogVice\PHPLogger\Information;

abstract class InformationContract
{
    /**
     * @return string
     */
    public function getClassName()
    {
        $path = explode('\\', get_class($this));

        return array_pop($path);
    }

    /**
     * @return array
     */
    abstract public function info();
}
