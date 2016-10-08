<?php namespace LogVice\PHPLogger;

/*
 * This file is part of \LogVice\PHPLogger package.
 *
 * (c) Alban Kora <ankdeveloper@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

class Backtrace
{
    protected $active;

    /**
     * @var array
     */
    protected $traces = [];

    public function __construct($active = false)
    {
        $this->active = $active;
    }

    /**
     * @param array $traces
     */
    public function setTraces($traces)
    {
        $this->traces = $traces;
    }

    public function info()
    {
        if ($this->active === false) {
            return '';
        }

        if (empty($this->traces)) {
            $this->traces = debug_backtrace();
        }

        return $this->formatTraces();
    }

    protected function formatTraces()
    {
        $backtrace = [];

        foreach ($this->traces as $row) {
            if (isset($row['file']) && strpos($row['file'], 'phplogger') === false) {
                $backtrace[] = $this->setBacktraceValues($row);
            }
        }

        return $backtrace;
    }

    protected function setBacktraceValues($row)
    {
        return [
            'file' => isset($row['file']) ? $row['file'] : null,
            'line' => isset($row['line']) ? $row['line'] : null,
            'class' => isset($row['class']) ? $row['class'] : null,
            'function' => isset($row['function']) ? $row['function'] : null,
            'args' => isset($row['args']) ? $row['args'] : null,
        ];
    }
}
