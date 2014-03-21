<?php

namespace Meetingroom\Entity\Event;

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
}
