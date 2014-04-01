<?php

namespace Meetingroom\Entity\User;

class UserManager
{
    protected $userModel = null;

    public function getUserModel()
    {
        if ($this->userModel === null) {
            $this->userModel = new \Meetingroom\Model\User\UserModel();
        }

        return $this->userModel;
    }

    public function getIdByUsername($username)
    {
        return $this->getUserModel()->getIdByUsername($username);
    }
}
