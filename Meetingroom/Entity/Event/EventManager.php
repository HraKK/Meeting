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

    public function createEvent($title, $userId, $roomId, $dateStart, $dateEnd, $description = '', $repeatable = 0, $attendees = 0) 
    {
        return $this->getEventModel()->create([
            'room_id' => $roomId, 
            'date_start' => $dateStart, 
            'date_end' => $dateEnd, 
            'user_id' => $userId, 
            'title' => $title, 
            'description' => $description, 
            'repeatable' => $repeatable, 
            'attendees' => $attendees
        ]);
    }
    
    public function deleteEvent($eventId)
    {
        return $this->getEventModel()->delete($eventId);
    }
}
