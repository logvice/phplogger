<?php

namespace LogVice\PHPLogger\Information;

class Backtrace extends AbstractInformation
{
    protected $traces = [];

    protected $skipFunctions = array(
        'call_user_func',
        'call_user_func_array',
    );

    /**
     * @param array $traces
     */
    public function setTraces($traces)
    {
        $this->traces = $traces;
    }

    public function make()
    {
        if (empty($traces)) {
            $traces = debug_backtrace();
        }
        // skip first since it's always the current method
        array_shift($traces);
        // the call_user_func call is also skipped
        array_shift($traces);

        return ['info' => $this->handle()];
    }

    protected function handle()
    {
        $backtrace = [];

        foreach ($this->traces as $row) {
            $backtrace[] = [
                'file' => isset($row['file']) ? $row['file'] : null,
                'line' => isset($row['line']) ? $row['line'] : null,
                'class' => isset($row['class']) ? $row['class'] : null,
                'function' => isset($row['function']) ? $row['function'] : null,
                'args' => isset($row['args']) ? $row['args'] : null,
            ];
        }

        return $backtrace;
    }
}
