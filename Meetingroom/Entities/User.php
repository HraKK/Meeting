<?php

namespace Meetingroom\Entities;

class User extends AbstractEntity implements UserInterface
{
    public function __construct() 
    {
        parent::__construct();
    }
    
    public function load($username) 
    {
        $model = new \Meetingroom\Models\UserModel();
        $this->id = $model->getId($username);
        return $this;
    }
    
    public function getId() 
    {
        return $this->id;
    }
}