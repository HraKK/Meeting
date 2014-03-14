<?php

namespace Meetingroom\Entity;

use \Meetingroom\Entity\Role\HasRoleInterface;
use \Meetingroom\Entity\User\UserInterface;

class Event extends \Meetingroom\Entity\AbstractEntity implements HasRoleInterface
{
    protected $id = null;
    protected $userId = 0;

    public function __construct($id)
    {
        $model = new \Meetingroom\Model\EventModel();
        
        if($model->eventExist($id)) {
            $this->id = (int) $id;
            $this->userId = $model->getUserIdByEventId($this->id);
        }
        
        return $this;
    }

    public function userRole(UserInterface $user)
    {
        $id = $user->getId();
    }
}
