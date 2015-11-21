<?php

namespace LogVice\PHPLogger\Information;

class Request extends AbstractInformation
{
    public function make()
    {
        $this->attributes = [
            'url' => $this->currentUrl(),
            'method' => $this->requestMethodData(),
            'params' => $this->postParamsData(),
            'user_ip' => $this->requestIp(),
            'agent' => $this->userAgentData(),
            'headers' => $this->requestHeadersData(),
            'session' => $this->sessionData(),
            'cookie' => $this->cookieData()
        ];
    }

    public function currentUrl()
    {
        $schema = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
        $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        return $schema . $host . $uri;
    }

    public function requestMethodData()
    {
        if (isset($_SERVER['REQUEST_METHOD'])) {
            return $_SERVER['REQUEST_METHOD'];
        }

        return null;
    }

    public function postParamsData()
    {
        if (empty($_POST)) {
            return null;
        }

        return $_POST;
    }

    public static function requestIp()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }

        return false;
    }

    public function userAgentData()
    {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            return $_SERVER['HTTP_USER_AGENT'];
        }

        return null;
    }

    public function requestHeadersData()
    {
        if (function_exists('getallheaders')) {
            return getallheaders();
        }

        return $this->processHeaders();
    }

    protected function processHeaders()
    {
        $headers = [];

        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) === 'HTTP_') {
                $headers[strtolower(substr($name, 5))] = $value;
            }
        }

        return (empty($headers) ? null : $headers);
    }

    public function sessionData()
    {
        if (empty($_SESSION)) {
            return null;
        }

        return $_SESSION;
    }

    public function cookieData()
    {
        if (empty($_COOKIE)) {
            return null;
        }

        return $_COOKIE;
    }
}
