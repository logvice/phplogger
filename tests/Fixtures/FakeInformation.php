<?php

namespace LogVice\PHPLogger\Fixtures;

use LogVice\PHPLogger\Information\AbstractInformation;

class FakeInformation extends AbstractInformation {
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