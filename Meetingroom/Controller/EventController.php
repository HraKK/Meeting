<?php

namespace Meetingroom\Controller;

use \Meetingroom\Entity\Role\RoleFactory;
use \Meetingroom\Entity\User\UserFactory;
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


    public function checkConflictAction($id = 0)
    {
        $di = $this->getDI();
        $lookupper = new EventLookupper($di);

        $entity = new \Meetingroom\Entity\Event\EventEntity();
        $event_fields = [
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
            $eventDTO = new \Meetingroom\DTO\Event\EventDTO();
            $eventDTO->id = $event->id;
            $eventDTO->roomId = $event->roomId;
            $eventDTO->dateStart = $event->dateStart;
            $eventDTO->dateEnd = $event->dateEnd;
            $eventDTO->userId = $event->userId;
            $eventDTO->title = $event->title;
            $eventDTO->desription = $event->desription;
            $eventDTO->attendees = $event->attendees;

            $eventsDTO[] = $eventDTO;

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
        $username = $this->session->get('username');
        
        if($username == false) {
            die('session timed out');
        }
        
        $userManager = new UserManager();
        $userId = $userManager->getIdByUsername($username);
        
        if ($userId === false) {
            die('user not exist');
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
        $event->bind([
            'title' => $this->request->getPost("title", "striptags"),
            'room_id' => $roomId,
            'user_id' => $userId,
            'date_start' => $this->request->getPost("date_start", "string"),
            'date_end' => $this->request->getPost("date_end", "string"),
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
        $event = $this->validateEvent();

        $roomId = $this->request->getPost("room_id", "int");
        
        if($roomId !== $event->roomId) {
            $roomManager = new RoomManager();
            if(!$roomManager->isRoomExist($roomId)) {
                die('room ain`t exist');
            }
        }
         
        $isRepeatable = $this->request->getPost("repeatable", "int");
        $check = $event->bind([
            'title' => $this->request->getPost("title", "striptags"),
            'room_id' => $roomId,
            'date_start' => $this->request->getPost("date_start", "string"),
            'date_end' => $this->request->getPost("date_end", "string"),
            'description' => $this->request->getPost("description", "striptags"),
            'repeatable' => $isRepeatable,
            'attendees' => $this->request->getPost("attendees", "int"),
        ])->save();
        
        
        if(!($check && $isRepeatable)) {
            var_dump($check);
            exit;
        }
        
        $option = new EventOptionEntity();
        $res = $option->bind([
            'id' => $event->id,
            'mon' => $this->request->getPost("mon", "int"),
            'tue' => $this->request->getPost("tue", "int"),
            'wed' => $this->request->getPost("wed", "int"),
            'thu' => $this->request->getPost("thu", "int"),
            'fri' => $this->request->getPost("fri", "int"),
            'sat' => $this->request->getPost("sat", "int"),
            'sun' => $this->request->getPost("sun", "int"),
        ])->update();
        
        print_r($res);
        exit;
    }
    
    public function deleteAction()
    {
        $event = $this->validateEvent();
        echo $event->delete() ? 'success' : 'false';
        exit;
    }
    
    private function validateEvent()
    {
        $username = $this->session->get('username');
        
        if($username == false) {
            die('session timed out');
        }
        
        $user = (new UserFactory())->getUser($username);
        
        if ($user->getId() == false) {
            die('user not found');
        }
        
        $eventId = $this->request->getPost("event_id", "int");
        
        $event = new EventEntity($eventId);
        if($event->isLoaded() === false) {
            die('event not found');
        }
        
        $role = (new RoleFactory())->getRole($user, $event);
        
        if($role !== Group::OWNER) {
            die('user is not owner');
        }
        
        return $event;
    }
}
