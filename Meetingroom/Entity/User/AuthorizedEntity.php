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
    protected $userModel;
    
    protected $fields = [
        'id' => 'id', 
        'name' => 'name', 
        'phone' => 'phone', 
        'position' => 'position', 
        'nickname' => 'nickname'
    ];
    
    public function loadByUsername($username)
    {
        $this->id = $this->getUserModel()->getIdByUsername($username);
        return $this;
    }

    public function getUserModel()
    {
        return ($this->userModel = $this->userModel === null ? new $this->modelName : $this->userModel);
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getNickname()
    {
        return $this->nickname;
    }
}
