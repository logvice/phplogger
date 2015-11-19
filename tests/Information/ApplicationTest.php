<?php

/*
 * This file is part of \LogVice\PHPLogger package.
 *
 * (c) Alban Nikolaos Kora <ankdeveloper@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace LogVice\PHPLogger\Information;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    protected $application;

    protected function setUp()
    {
        $this->application = new Application();
    }

    public function testClassName()
    {
        $this->assertEquals('Application', $this->application->getClassName());
    }

    public function testInfoFunction()
    {

        $this->application->setUser('test');
        $expected = [
            'user' => 'test'
        ];

        $this->assertEquals($expected, $this->application->info());
    }
}
