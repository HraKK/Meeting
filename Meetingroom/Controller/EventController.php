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
        $model = new \Meetingroom\Model\Event\EventModel();
        var_dump($model->read(666));
        exit;
    }


    public function checkConflictAction($id = 0)
    {
        $di = $this->getDI();
        $lookupper = new EventLookupper($di);
        $entity = new \Meetingroom\Entity\Event\EventEntity(1);
        //var_dump('<pre>',$entity);
        $lookupper->checkConflict($entity);

        die('--fin--');
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
        
        $event = new EventEntity();
        $check = $event->bind([
            'title' => $this->request->getPost("title", "striptags"),
            'room_id' => $roomId,
            'user_id' => $userId,
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
        ])->insert();
        
        print_r($res);
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
