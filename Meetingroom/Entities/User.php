<?php

namespace Meetingroom\Entities;

class User extends \Meetingroom\Entities\AbstractEntity implements \Meetingroom\Entities\User\UserInterface
{
    protected $id = null;
    
    public function init($username) 
    {
        $model = new \Meetingroom\Models\UserModel();
        $this->id = $model->getId($username);
        return $this;
    }
    
    public function getId() 
    {
        return $this->id;
    }
    
    public function getRole() 
    {
        return 'ROLE_USER';
    }
}