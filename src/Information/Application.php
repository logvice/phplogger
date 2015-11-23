<?php

namespace LogVice\PHPLogger\Information;

class Application extends AbstractInformation
{
    protected $user;

    public function info()
    {
        return [
            'user' => $this->user,
        ];
    }

    /**
     * @param null $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}
