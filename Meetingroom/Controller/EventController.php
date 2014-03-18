<?php

namespace Meetingroom\Controller;

use \Meetingroom\Entity\Role\RoleFactory;
use \Meetingroom\Entity\User\UserFactory;
use \Meetingroom\Entity\User\UserManager;
use \Meetingroom\Entity\Room\RoomManager;
use \Meetingroom\Entity\Event\EventManager;
use \Meetingroom\Entity\Event\EventEntity;
use \Meetingroom\Entity\Event\Lookupper\EventLookupper;
use \Meetingroom\Entity\Event\Lookupper\Criteria\DayPeriodCriteria;

use \Meetingroom\Entity\Role\Group;

class EventController extends AbstractController
{
    public function indexAction()
    {

    }

    public function lookuperAction($id = 0)
    {
        $di = $this->getDI();


        $roomCriteria = new \Meetingroom\Entity\Event\Lookupper\Criteria\RoomCriteria(1);
        //$periodCriteria = new \Meetingroom\Entity\Event\Lookupper\Criteria\WeekPeriodCriteria(17,3, 2014); // test week
        //$periodCriteria = new \Meetingroom\Entity\Event\Lookupper\Criteria\MonthPeriodCriteria(3, 2014);   // test month
        $periodCriteria = new DayPeriodCriteria(17, 3, 2014);
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
        // @todo
        $this->session->set('username', 'Barif2');
        $username = $this->session->get('username');
        
        $userManager = new UserManager();
        $userId = $userManager->getUserId($username);
        
        if ($userId === false) {
            // @todo
            $userId = $userManager->createUser($username, 1, 'developer', 'barif');
        }
        
        $roomId = $this->request->getPost("room_id", "int");
        $roomManager = new RoomManager();
        $isRoom = $roomManager->isRoomExist($roomId);

        if(!$isRoom) {
            echo json_encode(['status' => 'error']);
        }
        
        $event = new EventEntity();
        $event->bind([
            'title' => $this->request->getPost("title", "striptags"),
            'room_id' => $roomId,
            'user_id' => $userId,
            'date_start' => $this->request->getPost("date_start", "string"),
            'date_end' => $this->request->getPost("date_end", "string"),
            'description' => $this->request->getPost("description", "striptags"),
            'repeatable' => $this->request->getPost("repeatable", "int"),
            'attendees' => $this->request->getPost("attendees", "int"),
        ]);
        
        $check = $event->save();
        // @todo repeatable
        var_dump($check);
        exit;
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
         
        // @todo repeatable, check period

        $event->bind([
            'title' => $this->request->getPost("title", "striptags"),
            'room_id' => $roomId,
            'date_start' => $this->request->getPost("date_start", "string"),
            'date_end' => $this->request->getPost("date_end", "string"),
            'description' => $this->request->getPost("description", "striptags"),
            'repeatable' => $this->request->getPost("repeatable", "int"),
            'attendees' => $this->request->getPost("attendees", "int"),
        ]);
        
        $update = $event->save();
        var_dump($update);
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
        $this->session->set('username', 'Barif2');
        $username = $this->session->get('username');
        
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
