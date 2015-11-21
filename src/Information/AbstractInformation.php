<?php

namespace LogVice\PHPLogger\Information;

use LogVice\PHPLogger\Contracts\InformationInterface;

abstract class AbstractInformation implements InformationInterface
{
    /**
     * Information attributes container
     *
     * @var array
     */
    protected $attributes = [];

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
    public function info()
    {
        $this->make();

        return $this->attributes;
    }
}
