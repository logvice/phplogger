<?php

namespace LogVice\PHPLogger\Information;

class Request extends AbstractInformation
{
    public function info()
    {
        return array(
            'url' => $this->currentUrl(),
            'method' => $this->requestMethodData(),
            'params' => $this->postParamsData(),
            'user_ip' => $this->requestIp(),
            'agent' => $this->userAgentData(),
            'headers' => $this->requestHeadersData(),
            'session' => $this->sessionData(),
            'cookie' => $this->cookieData()
        );
    }

    public function currentUrl()
    {
        $schema = (
            (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
            (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
        )
            ? 'https://'
            : 'http://';

        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
        $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';

        return $schema . $host . $uri;
    }

    public function  requestMethodData()
    {
        if (isset($_SERVER['REQUEST_METHOD'])) {
            return $_SERVER['REQUEST_METHOD'];
        }
        return null;
    }

    public function postParamsData()
    {
        if (isset($_POST) && !empty($_POST)) {
            return $_POST;
        } else if (isset($_SERVER['CONTENT_TYPE']) && stripos($_SERVER['CONTENT_TYPE'], 'application/json') === 0) {
            return json_decode(file_get_contents('php://input'));
        }
        return null;
    }

    public static function requestIp()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (isset($_SERVER['REMOTE_ADDR'])) {
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

        $headers = array();

        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return (empty($headers) ? null : $headers);
    }

    public function sessionData()
    {
        if (!empty($_SESSION)) {
            return $_SESSION;
        }
        return null;
    }

    public function cookieData()
    {
        if (!empty($_COOKIE)) {
            return $_COOKIE;
        }
        return null;
    }
}
