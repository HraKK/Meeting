<?php

namespace Meetingroom\Entities\User;

class Guest extends \Meetingroom\Entities\AbstractEntity implements \Meetingroom\Entities\User\UserInterface
{
    protected $id = null;
    
    public function init($username) 
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