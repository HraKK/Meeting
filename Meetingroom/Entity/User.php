<?php

namespace Meetingroom\Entity;

class User extends \Meetingroom\Entity\AbstractEntity implements \Meetingroom\Entity\User\UserInterface
{
    protected $id = null;

    public function __construct($username)
    {
        $model = new \Meetingroom\Model\UserModel();
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
