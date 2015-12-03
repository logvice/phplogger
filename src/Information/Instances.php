<?php

namespace LogVice\PHPLogger\Information;

class Instances extends InformationContract
{
    public function info()
    {
        return [
            'functions' => get_defined_functions(),
            'classes' => get_declared_classes(),
            'interfaces' => get_declared_interfaces()
        ];
    }
}
