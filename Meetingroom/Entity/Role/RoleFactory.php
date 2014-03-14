<?php

namespace Meetingroom\Entity\Role;

use \Meetingroom\Entity\User\UserInterface;
use \Meetingroom\Entity\OwnableInterface;

class RoleFactory
{
    public function getRole(UserInterface $user, OwnableInterface $obj)
    {
        if($user->getId() === null) {
            return Group::GUEST;
        }
        
        return ($obj->ownerId() === $user->getId()) ? Group::OWNER : Group::USER;
    }
}
