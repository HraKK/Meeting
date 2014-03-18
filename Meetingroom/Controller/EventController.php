<?php

namespace Meetingroom\Controller;

use \Meetingroom\Entity\Role\RoleFactory;
use \Meetingroom\Entity\User\UserFactory;
use \Meetingroom\Entity\User\UserManager;
use \Meetingroom\Entity\Room\RoomManager;
use \Meetingroom\Entity\Event\EventManager;
use \Meetingroom\Entity\Event\EventEntity;

class EventController extends AbstractController
{
    public function indexAction()
    {
        $eventManager = new EventManager();
        $events = $eventManager->loadEvents();
//        $events[1]->id = 3;
//        $id = $events[1]->id;
//        
//        var_dump($id);
        exit;
    }

    public function showAction($id = 0)
    {
        $event = new EventEntity($id);
        $user = (new UserFactory())->getUser('Alex');
        $role = (new RoleFactory())->getRole($user, $event);
        var_dump($role);
        exit;
    }


    public function lookuperAction($id = 0)
    {
        $di = $this->getDI();

        $lookupper = new \Meetingroom\Entity\Event\Lookupper\EventLookupper($di);

        $roomCriteria = new \Meetingroom\Entity\Event\Lookupper\Criteria\RoomCriteria(1);
        $periodCriteria = new \Meetingroom\Entity\Event\Lookupper\Criteria\DayPeriodCriteria(17, 3, 2014);
        $lookupper->setPeriodCriteria($periodCriteria);
        $lookupper->setRoomCriteria($roomCriteria);
        var_dump('<pre>', $lookupper->lookup());


        die();
    }

    
    public function createAction()
    {
        $this->session->set('username', 'Barif2');
        $username = $this->session->get('username');
        
        $userManager = new UserManager();
        $userId = $userManager->getUserId($username);
        
        if ($userId === false) {
            $userId = $userManager->createUser($username, 1, 'developer', 'barif');
        }
        
        $roomId = $this->request->getPost("room_id", "int");
        $roomManager = new RoomManager();
        $isRoom = $roomManager->isRoomExist($roomId);

        if(!$isRoom) {
            echo json_encode(['status' => 'error']);
        }
        
        $eventManager = new EventManager();
        
        $event = $eventManager->createEvent(
                $this->request->getPost("title", "striptags"), 
                $userId, 
                $roomId, 
                $this->request->getPost("date_start", "string"), 
                $this->request->getPost("date_end", "string"), 
                $this->request->getPost("description", "striptags"), 
                $this->request->getPost("repeatable", "int"),  
                $this->request->getPost("attendies", "int")
        );
        
        var_dump($event);
        exit;
    }
}
