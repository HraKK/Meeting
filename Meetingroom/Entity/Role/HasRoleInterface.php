<?php

namespace Meetingroom\Entity\Role;

use \Meetingroom\Entity\User\UserInterface;

interface HasRoleInterface
{
    public function userRole(UserInterface $user);
}
