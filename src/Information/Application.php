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

class Application extends AbstractInformation
{
    protected $user;

    public function info()
    {
        return array(
            'user' => $this->getUser()
        );
    }

    /*
     * Get request headers
     *
     * @return array
     */

    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param null $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}
