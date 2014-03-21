<?php

namespace Meetingroom\Controller;

use \Meetingroom\Entity\Room\RoomManager;
use \Meetingroom\Entity\Event\EventOptionEntity;
use \Meetingroom\Entity\Event\EventEntity;
use \Meetingroom\Entity\Event\Lookupper\EventLookupper;
use \Meetingroom\Entity\Event\Lookupper\Criteria\DayPeriodCriteria;
use \Meetingroom\Validate\Timestamp\Timestamp;
use \Meetingroom\Validate\Timestamp\TimestampCompare;

class EventController extends AbstractController
{
    public function indexAction()
    {
        
    }


    public function checkIsConflictAction($id = 0)
    {
        $di = $this->getDI();
        $lookupper = new EventLookupper($di);

        $entity = new \Meetingroom\Entity\Event\EventEntity();

        $event_fields = [
            //'id'=>'',///null,//4, //test exclude
            'room_id' => '1',
            'date_start' => '2014-01-22 18:00:00',
            'date_end' => '2014-01-22 18:10:00',
            'user_id' => '1',
            'title' => 'test',
            'description' => 'description',
            'repeatable' => true,
            'attendees' => 4
        ];
        $entity->bind($event_fields);


        $options = new \Meetingroom\Entity\Event\EventOptionEntity();
        $options_fields = [
            'mon' => true,
            'tue' => true,
            'wed' => true,
            'thu' => false,
            'fri' => true,
            'sat' => false,
            'sun' => true,
        ];
        $options->bind($options_fields);

        $conflict_events = $lookupper->checkIsConflict($entity, $options);
        $eventsDTO = [];
        foreach ($conflict_events as $event) {

            $eventsDTO[] = $event->getDTO();

        }

        var_dump('<pre>', $eventsDTO);
        die();
    }

    public function lookuperAction($id = 0)
    {
        $di = $this->getDI();
        $roomCriteria = new \Meetingroom\Entity\Event\Lookupper\Criteria\RoomCriteria(1);
        //$periodCriteria = new \Meetingroom\Entity\Event\Lookupper\Criteria\WeekPeriodCriteria(17,3, 2014); // test week
        //$periodCriteria = new \Meetingroom\Entity\Event\Lookupper\Criteria\MonthPeriodCriteria(3, 2014);   // test month
        $periodCriteria = new DayPeriodCriteria(18, 3, 2014);
        $lookupper = new EventLookupper($di);

        var_dump(
            '<pre>',
            $lookupper->setPeriodCriteria($periodCriteria)->setRoomCriteria($roomCriteria)->setFields(
                ['id', 'title']
            )->lookup()
        );

        die();
    }

    
    public function createAction()
    {
        if(!$this->isAllowed('event', 'create')) {
            $this->onDenied();
        }
        
        $title = $this->request->getPost("title", "striptags");
        $roomId = $this->request->getPost("room_id", "int");
        $isRepeatable = $this->request->getPost("repeatable", "int");
        $dateStart = $this->request->getPost("date_start", "string");
        $dateEnd = $this->request->getPost("date_end", "string");
        $description = $this->request->getPost("description", "striptags");
        $attendees = $this->request->getPost("attendees", "int");
        
        $mon = $this->request->getPost("mon", "int");
        $tue = $this->request->getPost("tue", "int");
        $wed = $this->request->getPost("wed", "int");
        $thu = $this->request->getPost("thu", "int");
        $fri = $this->request->getPost("fri", "int");
        $sat = $this->request->getPost("sat", "int");
        $sun = $this->request->getPost("sun", "int");
        
        if(strlen($title) < 3) {
            die('Title should be longer');
        }
        
        $roomManager = new RoomManager();
        if(!$roomManager->isRoomExist($roomId)) {
            die('room ain`t exist');
        }

        $lookupper = new EventLookupper($this->di);
        $event = new EventEntity();
        
        $start = strtotime($dateStart);
        $end = strtotime($dateEnd);
        
        if($start === false || $end === false || $end <= $start) {
            die('error');
        }
        
        $event->bind([
            'title' => $title,
            'room_id' => $roomId,
            'user_id' => $this->user->id,
            'date_start' => $start,
            'date_end' => $end,
            'description' => $description,
            'repeatable' => $isRepeatable,
            'attendees' => $attendees
        ]);
        
        $option = new EventOptionEntity();
        
        if($isRepeatable) {
            $option->bind([
                'id' => $event->id,
                'mon' => $mon,
                'tue' => $tue,
                'wed' => $wed,
                'thu' => $thu,
                'fri' => $fri,
                'sat' => $sat,
                'sun' => $sun,
            ]);
        }
        
        $conflict = $lookupper->checkIsConflict($event, $option);
        
        if(!$conflict) {
            $eventId = $event->save();
            if(!$eventId){
                die('event not created');
            }
            
            if($isRepeatable) {
                $option->bind(['id' => $event->id])->insert();
            }
            
            die($eventId);
        } else {
            var_dump($conflict);
            exit;
        }
    }
    
    public function updateAction()
    {
        $event = $this->getEventByRequest();
        
        $role = $this->getRoleFactory()->getRoleInEvent($this->user, $event);
        if(!$this->isAllowed('event', 'update', $role)) {
            $this->onDenied();
        }
        
        $title = $this->request->getPost("title", "striptags");
        $roomId = $this->request->getPost("room_id", "int");
        $isRepeatable = $this->request->getPost("repeatable", "int");
        $dateStart = $this->request->getPost("date_start", "string");
        $dateEnd = $this->request->getPost("date_end", "string");
        $description = $this->request->getPost("description", "striptags");
        $attendees = $this->request->getPost("attendees", "int");
        
        $mon = $this->request->getPost("mon", "int");
        $tue = $this->request->getPost("tue", "int");
        $wed = $this->request->getPost("wed", "int");
        $thu = $this->request->getPost("thu", "int");
        $fri = $this->request->getPost("fri", "int");
        $sat = $this->request->getPost("sat", "int");
        $sun = $this->request->getPost("sun", "int");
        
        if(strlen($title) < 3) {
            die('Title should be longer');
        }
        
        if($roomId !== $event->roomId) {
            $roomManager = new RoomManager();
            if(!$roomManager->isRoomExist($roomId)) {
                die('room ain`t exist');
            }
        }
         
        $lookupper = new EventLookupper($this->di);
        
        $start = strtotime($dateStart);
        $end = strtotime($dateEnd);
        
        if($start === false || $end === false || $end <= $start) {
            die('error');
        }
        
        $event->bind([
            'title' => $title,
            'room_id' => $roomId,
            'date_start' => $start,
            'date_end' => $end,
            'description' => $description,
            'repeatable' => $isRepeatable,
            'attendees' => $attendees
        ]);
        
        $option = new EventOptionEntity();
        
        if($isRepeatable) {
            $option->bind([
                'id' => $event->id,
                'mon' => $mon,
                'tue' => $tue,
                'wed' => $wed,
                'thu' => $thu,
                'fri' => $fri,
                'sat' => $sat,
                'sun' => $sun
            ]);
        }
        
        $conflict = $lookupper->checkIsConflict($event, $option);
        
        if(!$conflict) {
            $eventId = $event->save();
            
            if($isRepeatable) {
                $option->update();
            }
            
            die($eventId);
        } else {
            var_dump($conflict);
            exit;
        }
    }
    
    public function deleteAction()
    {
        $event = $this->getEventByRequest();
        
        $role = $this->getRoleFactory()->getRoleInEvent($this->user, $event);
        if(!$this->isAllowed('event', 'delete', $role)) {
            $this->onDenied();
        }
        
        echo $event->delete() ? 'success' : 'false';
        exit;
    }
    
    protected function getEventByRequest()
    {
        $eventId = $this->request->getPost("event_id", "int");
        
        $event = new EventEntity($eventId);
        if($event->isLoaded() === false) {
            die('event not found');
        }
        
        return $event;
    }
}
