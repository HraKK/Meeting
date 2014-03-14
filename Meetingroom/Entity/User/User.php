<?php

namespace Meetingroom\Entity\User;

class User extends \Meetingroom\Entity\AbstractEntity implements \Meetingroom\Entity\User\UserInterface
{
    protected $id = null;

    public function __construct()
    {
        return $this;
    }
    
    public function load($username)
    {
        $model = new \Meetingroom\Model\UserModel();
        $this->id = $model->getIdByUsername($username);
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }
}
