<?php

namespace LogVice\PHPLogger\Fixtures;

use LogVice\PHPLogger\Information\InformationContract;

class FakeInformation extends InformationContract
{
    /**
     * Return the information
     *
     * @return array
     */
    public function info()
    {
        return [];
    }

}