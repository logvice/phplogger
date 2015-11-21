<?php

namespace LogVice\PHPLogger\Information;

class Backtrace extends AbstractInformation
{
    protected $backtrace;
    protected $traces = [];

    public function make()
    {
        $this->attributes['backtrace'] = $this->handle($this->getBacktrace());
    }

    public function getTraces()
    {
        return $this->traces;
    }

    /**
     * @param array $backtrace
     * @return array
     */
    public function handle(array $backtrace = [])
    {
        $this->composeTrace($backtrace);

        if (isset($backtrace['backtrace'])) {
            foreach ($backtrace['backtrace'] as $row) {
                $this->composeTrace($row);
            }
        }

        return $this->getTraces();
    }

    protected function composeTrace($row)
    {
        $data = ['file' => null, 'line' => null, 'function' => null, 'class' => null];

        foreach ($row as $key => $value) {
            $data[$key] = $value;
        }

        $this->addTrace($data['file'], $data['line'], $data['function'], $data['class']);
    }

    /**
     * PHP backtrace's are misaligned, we need to shift the file/line down a frame
     *
     * @param $file
     * @param $line
     * @param $function
     * @param null $class
     */
    public function addTrace($file, $line, $function, $class = null)
    {
        $this->traces[] = [
            'line' => $line,
            'function' => $function,
            'file' => $file,
            'class' => $class
        ];
    }

    /**
     * Get backtrace array
     *
     * @return array
     */
    protected function getBacktrace()
    {
        $backtrace = [];

        if (version_compare(PHP_VERSION, '5.3.6') >= 0) {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS & ~DEBUG_BACKTRACE_PROVIDE_OBJECT);
        } elseif (version_compare(PHP_VERSION, '5.2.5') >= 0) {
            $backtrace = debug_backtrace(false);
        } else {
            $backtrace = debug_backtrace();
        }

        return $backtrace;
    }
}
