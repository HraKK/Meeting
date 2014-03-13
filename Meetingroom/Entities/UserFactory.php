<?php

namespace Meetingroom\Entities;

class UserFactory extends AbstractFactory
{
    public function loadUser($username) 
    {
        $user = new \Meetingroom\Entities\User();
        return $user->load($username);
    }
}
