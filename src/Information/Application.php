<?php

namespace LogVice\PHPLogger\Information;

class Application extends AbstractInformation
{
    protected $user;

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
