<?php

namespace Meetingroom\Entity\Event;

class EventFactory
{
    public function getEvent($id)
    {
        $model = new \Meetingroom\Model\EventModel();
        return $model->eventExist($id) ? (new Event())->load($id) : (new Event());
    }
}
