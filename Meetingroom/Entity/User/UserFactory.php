<?php

namespace Meetingroom\Entity\User;

class UserFactory
{
    public function getUser($username)
    {
        $model = $this->getUserModel();
        $userId = $model->getIdByUsername($username);

        if ($userId === false) {
            $user = $this->getGuestEntity();
        } else {
            $user = $this->getAuthorizedEntity();
            $user->loadByUsername($username);
        }
        
        return $user;
    }
    
    public function getGuestEntity()
    {
        return new GuestEntity();
    }

    public function getUserModel()
    {
        return new \Meetingroom\Model\User\UserModel();
    }
    
    public function getAuthorizedEntity()
    {
        return new AuthorizedEntity();
    }
}
