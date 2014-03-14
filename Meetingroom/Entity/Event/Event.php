<?php

namespace Meetingroom\Entity\Event;

use Meetingroom\Entity\OwnableInterface;

class Event extends \Meetingroom\Entity\AbstractEntity implements OwnableInterface
{
    protected $id = null;
    protected $userId = 0;

    public function __construct()
    {
        return $this;
    }

    public function load($id)
    {
        $model = new \Meetingroom\Model\EventModel();
        $data = $model->getEventData($id);
        if($data) {
            $this->id = $data->id;
            $this->userId = $data->user_id;
        }
            
        return $this;
    }

    public function ownerId()
    {
        return $this->userId;
    }
}
