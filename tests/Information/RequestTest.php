<?php

namespace LogVice\PHPLogger\Information;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    protected $request;

    protected function setUp()
    {
        $this->request = new Request();
    }

    public function testClassName()
    {
        $this->assertEquals('Request', $this->request->getClassName());
    }

    public function testEmptyMake()
    {
        $expected = [
            'url' => 'http://',
            'method' => null,
            'params' => null,
            'user_ip' => false,
            'agent' => null,
            'headers' => null,
            'session' => null,
            'cookie' => null
        ];

        $this->request->make();

        $this->assertEquals($expected, $this->request->info());
    }

    public function testCurrentUrl(){

        $_SERVER['HTTP_HOST'] = 'test.com';
        $_SERVER['REQUEST_URI'] = '/test';
        $this->assertEquals('http://test.com/test', $this->request->currentUrl());
        $_SERVER['HTTPS'] = 'on';
        $this->assertEquals('https://test.com/test', $this->request->currentUrl());
        unset($_SERVER['HTTP_HOST']);
        unset($_SERVER['REQUEST_URI']);
        unset($_SERVER['HTTPS']);
    }

    public function testRequestMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertEquals('POST', $this->request->requestMethodData());
    }

    public function testCookiesMethod()
    {
        $this->assertNull($this->request->cookieData());
        $_COOKIE['test'] = 'lol';
        $this->assertEquals(['test' => 'lol'], $this->request->cookieData());
    }

    public function testRequestIp()
    {
        $this->assertFalse($this->request->requestIp());

        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $this->assertEquals('127.0.0.1', $this->request->requestIp());
        unset($_SERVER['REMOTE_ADDR']);

        $_SERVER['HTTP_X_FORWARDED_FOR'] = '127.0.0.1';
        $this->assertEquals('127.0.0.1', $this->request->requestIp());
        unset($_SERVER['HTTP_X_FORWARDED_FOR']);

        $this->assertFalse($this->request->requestIp());
    }

    public function testUserAgentData()
    {
        $this->assertNull($this->request->userAgentData());
        $_SERVER['HTTP_USER_AGENT'] = 'this is a test user agent';
        $this->assertEquals('this is a test user agent', $this->request->userAgentData());
        unset($_SERVER['HTTP_USER_AGENT']);
    }

    public function testRequestHeadersData()
    {
        $this->assertNull($this->request->requestHeadersData());

        $_SERVER['HTTP_USER_AGENT'] = 'this is a test user agent';
        $expected['user_agent'] = 'this is a test user agent';

        $this->assertEquals($expected, $this->request->requestHeadersData());

        unset($_SERVER['HTTP_USER_AGENT']);
    }

    public function testSessionData()
    {
        $this->assertNull($this->request->sessionData());
        $_SESSION['test'] = 'test';
        $this->assertEquals(['test' => 'test'], $this->request->sessionData());
    }

    public function testPostData()
    {
        $this->assertNull($this->request->postParamsData());
        $_POST['test'] = 'test';
        $this->assertEquals(['test' => 'test'], $this->request->postParamsData());
    }
}
