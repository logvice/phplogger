<?php namespace LogVice\PHPLogger\Output;

/*
 * This file is part of \LogVice\PHPLogger package.
 *
 * (c) Alban Kora <ankdeveloper@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

class TCPOutput implements OutputContract
{
    /**
     * @var resource
     */
    protected $curl;

    /**
     * @var string
     */
    protected $address;

    /**
     * @var string
     */
    protected $port;

    /**
     * @var int
     */
    protected $timeout;

    public function __construct($address, $port = '80', $timeout = 10)
    {
        $this->address = $address;
        $this->port = $port;
        $this->timeout = $timeout;
    }

    /**
     * Writes a message
     * @param mixed $logData
     */
    public function send($logData)
    {
        $this->config($logData);
        $this->run();
        $this->close();
    }

    /**
     * @param $logData
     */
    protected function config($logData)
    {
        $this->curl = curl_init($this->address);
        curl_setopt($this->curl, CURLOPT_PORT, $this->port);
        curl_setopt($this->curl, CURLOPT_HEADER, false);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $logData);
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curl, CURLOPT_VERBOSE, false);
        curl_setopt(
            $this->curl,
            CURLOPT_HTTPHEADER,
            [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($logData)
            ]
        );

        if (defined('HHVM_VERSION')) {
            curl_setopt($this->curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        } else {
            curl_setopt($this->curl, CURL_IPRESOLVE_V4, true);
        }
    }

    protected function run()
    {
        curl_exec($this->curl);
        $code = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        if ($code != 200) {
            throw new \Exception('PHPLogger cURL Error code: ' . $code);
        }

        if (curl_errno($this->curl)) {
            throw new \Exception('PHPLogger cURL Error ' . curl_error($this->curl));
        }
    }

    /**
     * Cleanup
     */
    protected function close()
    {
        if (is_resource($this->curl)) {
            curl_close($this->curl);
            $this->curl = null;
        }
    }
}
