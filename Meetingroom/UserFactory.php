<?php

namespace Meetingroom;

class UserFactory
{
    public function loadUser($username) 
    {
        $user = new \Meetingroom\Models\UserModel();
        return $user->load($username);
    }
}
