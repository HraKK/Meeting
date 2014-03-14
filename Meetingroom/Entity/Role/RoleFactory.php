<?php

namespace Meetingroom\Entity\Role;

use \Meetingroom\Entity\User\UserInterface;
use \Meetingroom\Entity\Role\HasRoleInterface;

class RoleFactory
{
    public function getRole(UserInterface $user, HasRoleInterface $obj)
    {
        return $obj->userRole($user);
    }
}
