<?php

namespace Meetingroom\Entity\Event;

use \Meetingroom\Entity\Event\Event;

class EventManager
{
    public function loadEvents()
    {
        $model = new \Meetingroom\Model\EventModel();
        $result = $model->getActiveEvents();
        
        $list = [];
        
        foreach ($result as $id => $data) {
            $list[$id] = (new Event())->bind($data);
        }
        
        return $list;
    }
}
