<?php

namespace Meetingroom\Entity\Role;

use \Meetingroom\Entity\User\UserInterface;
use \Meetingroom\Entity\OwnableInterface;

class RoleFactory
{
    public function getRoleInEvent(UserInterface $user, OwnableInterface $obj)
    {
        if($user->getId() === null) {
            return Group::GUEST;
        }
        
        return ($obj->getOwnerId() === $user->getId()) ? Group::OWNER : Group::USER;
    }
    
    public function getRole(UserInterface $user)
    {
        return ($user->getId() === null) ? Group::GUEST: Group::USER;
    }
}
