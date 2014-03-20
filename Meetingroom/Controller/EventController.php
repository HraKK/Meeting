<?php

namespace Meetingroom\Controller;

use \Meetingroom\Entity\Role\RoleFactory;
use \Meetingroom\Entity\User\UserManager;
use \Meetingroom\Entity\Room\RoomManager;
use \Meetingroom\Entity\Event\EventOptionEntity;
use \Meetingroom\Entity\Event\EventEntity;
use \Meetingroom\Entity\Event\Lookupper\EventLookupper;
use \Meetingroom\Entity\Event\Lookupper\Criteria\DayPeriodCriteria;

use \Meetingroom\Entity\Role\Group;

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
        $role = $this->session->get('role');
        $allow = $this->acl->isAllowed($role, 'event', 'create');
        if(!$allow) {
            die('Not permitted');
        }
        
        $roomId = $this->request->getPost("room_id", "int");
        $roomManager = new RoomManager();
        $isRoom = $roomManager->isRoomExist($roomId);

        if(!$isRoom) {
            echo json_encode(['status' => 'error']);
        }

        $isRepeatable = $this->request->getPost("repeatable", "int");
        $lookupper = new EventLookupper($this->di);
        
        $event = new EventEntity();

        $start = $this->request->getPost("date_start", "string");
        $dateStart = $this->validateTimestamp($start);
                
        $end = $this->request->getPost("date_end", "string");
        $dateEnd = $this->validateTimestamp($end);
                
        if($dateEnd==false || $dateStart==false) {
            die('Date MUST have format Y-m-d H:i:s');
        }
        var_dump([
            'title' => $this->request->getPost("title", "striptags"),
            'room_id' => $roomId,
            'user_id' => $this->user->id,
            'date_start' => $dateStart,
            'date_end' => $dateEnd,
            'description' => $this->request->getPost("description", "striptags"),
            'repeatable' => $isRepeatable,
            'attendees' => $this->request->getPost("attendees", "int"),
        ]);
        
        $option = new EventOptionEntity();
        
        if($isRepeatable) {
            $option->bind([
                'id' => $event->id,
                'mon' => $this->request->getPost("mon", "int"),
                'tue' => $this->request->getPost("tue", "int"),
                'wed' => $this->request->getPost("wed", "int"),
                'thu' => $this->request->getPost("thu", "int"),
                'fri' => $this->request->getPost("fri", "int"),
                'sat' => $this->request->getPost("sat", "int"),
                'sun' => $this->request->getPost("sun", "int"),
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
        
        $role = (new RoleFactory())->getRole($this->user, $event);
        
        $allow = $this->acl->isAllowed($role, 'event', 'update');
        if(!$allow) {
            die('Not permitted');
        }

        $roomId = $this->request->getPost("room_id", "int");
        
        if($roomId !== $event->roomId) {
            $roomManager = new RoomManager();
            if(!$roomManager->isRoomExist($roomId)) {
                die('room ain`t exist');
            }
        }
         
        $isRepeatable = $this->request->getPost("repeatable", "int");
        $lookupper = new EventLookupper($this->di);
        
        $start = $this->request->getPost("date_start", "string");
        $dateStart = $this->validateTimestamp($start);
                
        $end = $this->request->getPost("date_end", "string");
        $dateEnd = $this->validateTimestamp($end);
                
        if($dateEnd==false || $dateStart==false) {
            die('Date MUST have format Y-m-d H:i:s');
        }
        
        $event->bind([
            'title' => $this->request->getPost("title", "striptags"),
            'room_id' => $roomId,
            'date_start' => $start,
            'date_end' => $end,
            'description' => $this->request->getPost("description", "striptags"),
            'repeatable' => $isRepeatable,
            'attendees' => $this->request->getPost("attendees", "int"),
        ]);
        
        $option = new EventOptionEntity();
        
        if($isRepeatable) {
            $option->bind([
                'id' => $event->id,
                'mon' => $this->request->getPost("mon", "int"),
                'tue' => $this->request->getPost("tue", "int"),
                'wed' => $this->request->getPost("wed", "int"),
                'thu' => $this->request->getPost("thu", "int"),
                'fri' => $this->request->getPost("fri", "int"),
                'sat' => $this->request->getPost("sat", "int"),
                'sun' => $this->request->getPost("sun", "int"),
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
        
        $role = (new RoleFactory())->getRole($this->user, $event);
        
        $allow = $this->acl->isAllowed($role, 'event', 'delete');
        if(!$allow) {
            die('Not permitted');
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
    
    protected function validateTimestamp($timestamp) 
    {
        try {
            $date = new \DateTime($timestamp);
        } catch (\Exception $exc) {
            return false;
        }

        return ($date->format('Y-m-d H:i:s') == $timestamp) ? $timestamp : false;
    }
}
