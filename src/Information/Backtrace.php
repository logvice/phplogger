<?php

namespace LogVice\PHPLogger\Information;

class Backtrace extends AbstractInformation
{
    protected $backtrace;
    protected $traces = array();

    public function info()
    {
        $backtrace = $this->getBacktrace();

        return array(
            'backtrace' => $this->handle($backtrace)
        );
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
            foreach ($backtrace['backtrace'] as $rows) {
                $this->composeTrace($rows);
            }
        }

        return $this->getTraces();
    }

    protected function composeTrace($rows)
    {
        $file = isset($rows['file']) ? $rows['file'] : null;
        $line = isset($rows['line']) ? $rows['line'] : null;
        $function = isset($rows['function']) ? $rows['function'] : null;
        $class = isset($rows['class']) ? $rows['class'] : null;

        $this->addTrace($file, $line, $function, $class);
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
        $this->traces[] = array(
            'line' => $line,
            'function' => $function,
            'file' => $file,
            'class' => $class
        );
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
