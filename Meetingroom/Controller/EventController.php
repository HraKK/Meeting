<?php

namespace Meetingroom\Controller;

use \Meetingroom\Entity\Role\RoleFactory;
use \Meetingroom\Entity\User\UserFactory;
use \Meetingroom\Entity\Event\EventManager;
use \Meetingroom\Entity\Event\EventEntity;
use \Meetingroom\Model\EventModel;

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
        $model = new EventModel(['rooom_id', 'user_id']);
        $result = $model->read(2);
        
        var_dump($result);
        exit;
    }
}
