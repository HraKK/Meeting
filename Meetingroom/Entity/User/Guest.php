<?php

namespace Meetingroom\Entity\User;

class Guest extends \Meetingroom\Entity\AbstractEntity implements \Meetingroom\Entity\User\UserInterface
{
    protected $id = null;

    public function __construct()
    {
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getRole()
    {
        return 'ROLE_GUEST';
    }

}
