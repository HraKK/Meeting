<?php

namespace Meetingroom\Entity\Event;

use \Meetingroom\Entity\Event\EventEntity;

class EventManager
{
    protected $eventModel = null;

    public function getEventModel()
    {
        if ($this->eventModel === null) {
            $this->eventModel = new \Meetingroom\Model\EventModel();
        }

        return $this->eventModel;
    }

    public function loadEvents()
    {

        $result = $this->getEventModel()->getActiveEvents();

        $list = [];

        foreach ($result as $id => $data) {
            $list[$id] = (new EventEntity())->bind($data);
        }

        return $list;
    }

}