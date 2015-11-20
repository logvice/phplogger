<?php

namespace LogVice\PHPLogger\Output;

use LogVice\PHPLogger\Contracts\OutputInterface;

class UDPOutput implements OutputInterface
{
    const DATAGRAM_MAX_LENGTH = 65023;

    protected $address;
    protected $port;
    protected $header;
    protected $socket;

    /**
     * @param string $address
     * @param int $port
     * @param string $header
     */
    public function __construct($address, $port = 514, $header = '')
    {
        $this->address = $address;
        $this->port = $port;
        $this->header = $header;
    }

    /**
     * Writes a message
     *
     * @param mixed $logData
     * @return mixed
     * @throws \Exception
     */
    public function send($logData)
    {
        $this->open();

        $chunk = $this->header . substr($logData, 0, self::DATAGRAM_MAX_LENGTH - strlen($this->header));

        socket_sendto($this->socket, $chunk, strlen($chunk), $flags = 0, $this->address, $this->port);

        $this->close();

        return true;
    }

    /**
     * Create socket
     *
     * @throws \Exception
     */
    protected function open()
    {
        if (!function_exists('socket_create')) {
            throw new \Exception('socket_create function does not exist!');
        }

        $this->socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

        if (!is_resource($this->socket)) {
            throw new \Exception('PHPLogger can not send message to ' . $this->address . ':' . $this->port);
        }
    }

    /**
     * Cleanup
     */
    protected function close()
    {
        if (is_resource($this->socket)) {
            socket_close($this->socket);
            $this->socket = null;
        }
    }
}
