<?php

namespace Meetingroom\Entity\User;

class GuestEntity extends \Meetingroom\Entity\AbstractEntity implements \Meetingroom\Entity\User\UserInterface
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

    public function getId()
    {
        return null;
    }
}
