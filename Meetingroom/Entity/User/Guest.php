<?php

namespace Meetingroom\Entity\User;

class Guest extends \Meetingroom\Entity\AbstractEntity implements \Meetingroom\Entity\User\UserInterface
{
    public function __construct()
    {
        return $this;
    }

    public function getId()
    {
        return null;
    }
}
