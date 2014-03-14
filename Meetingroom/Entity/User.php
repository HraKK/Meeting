<?php

namespace Meetingroom\Entity;

use \Meetingroom\Entity\Role\HasRoleInterface;

class User extends \Meetingroom\Entity\AbstractEntity implements \Meetingroom\Entity\User\UserInterface
{
    protected $id = null;

    public function __construct($username)
    {
        $model = new \Meetingroom\Model\UserModel();
        $this->id = $model->getIdByUsername($username);
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getRole(HasRoleInterface $obj)
    {
        return $obj->userRole($this);
    }

}
