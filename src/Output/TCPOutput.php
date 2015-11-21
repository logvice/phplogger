<?php

namespace LogVice\PHPLogger\Output;

use LogVice\PHPLogger\Contracts\OutputInterface;

class TCPOutput implements OutputInterface
{
    protected $address;

    public function __construct($address, $timeout = 10)
    {
        $this->address = $address;
        $this->timeout = $timeout;
    }

    /**
     * Writes a message
     *
     * @param mixed $logData
     * @return mixed|void
     * @throws \Exception
     */
    public function send($logData)
    {
        $curl = $this->configCurl($logData);
        $data = $this->runCurl($curl);

        if ($data['code'] != 200) {
            throw new \Exception('PHPLogger cURL Error code: ' . $data['code']);
        }

        if (curl_errno($curl)) {
            throw new \Exception('PHPLogger cURL Error ' . curl_error($curl));
        }

        curl_close($curl);

        return true;
    }

    /**
     * @param $logData
     * @return resource
     */
    protected function configCurl($logData)
    {
        $curl = curl_init($this->address);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $logData);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_VERBOSE, false);
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($logData)
            )
        );

        if (defined('HHVM_VERSION')) {
            curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        } else {
            curl_setopt($curl, CURL_IPRESOLVE_V4, true);
        }

        return $curl;
    }

    /**
     * @param $curl
     * @return array
     */
    protected function runCurl($curl)
    {
        $data = array();
        $data['body'] = curl_exec($curl);
        $data['code'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        return $data;
    }
}
