<?php

namespace Meetingroom\Entity\User;

class UserManager
{
    protected $userModel = null;

    protected function getUserModel()
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
    
    public function createUser($username, $phone, $position, $nickname) 
    {
        return $this->getUserModel()->create([
            'name' => $username, 
            'phone' => $phone, 
            'position' => $position, 
            'nickname' => $nickname
        ]);
    }
}
