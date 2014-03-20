<?php

namespace Meetingroom\Controller;

use \Meetingroom\Entity\Room\RoomManager;
use \Meetingroom\Entity\Event\Lookupper\EventLookupper;
use \Meetingroom\Entity\Event\Lookupper\Criteria\RoomCriteria;
use \Meetingroom\Entity\Event\Lookupper\Criteria\DayPeriodCriteria;

class IndexController extends AbstractController
{
    public function indexAction()
    {
        $this->permitOrDie('index', 'index');
        
        $roomManager = new RoomManager();
        $rooms = $roomManager->getAll();
        
        $roomCriteria = new RoomCriteria(1);
        $periodCriteria = new DayPeriodCriteria(14, 3, 2014);
        $lookupper = new EventLookupper($this->di);

        $events = $lookupper
            ->setPeriodCriteria($periodCriteria)
            ->setRoomCriteria($roomCriteria)
            ->setFields(['id', 'title'])
            ->lookup();
//        print_r($events); exit;
    }
}
