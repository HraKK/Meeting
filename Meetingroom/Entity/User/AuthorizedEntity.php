<?php

namespace Meetingroom\Entity\User;

class AuthorizedEntity extends \Meetingroom\Entity\AbstractEntity implements \Meetingroom\Entity\User\UserInterface
{
    protected $modelName = '\Meetingroom\Model\User\UserModel';
    
    protected $id;
    protected $name;
    protected $phone;
    protected $position;
    protected $nickname;

    protected $fields = [
        'id' => 'id', 
        'name' => 'name', 
        'phone' => 'phone', 
        'position' => 'position', 
        'nickname' => 'nickname'
    ];
    
    public function charge($username)
    {
        $model = new \Meetingroom\Model\User\UserModel();
        $this->id = $model->getIdByUsername($username);
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }
}
