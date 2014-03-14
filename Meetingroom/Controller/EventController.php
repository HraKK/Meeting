<?php

namespace Meetingroom\Controller;

use \Meetingroom\Entity\Role\RoleFactory;
use \Meetingroom\Entity\User\UserFactory;
use \Meetingroom\Entity\Event\EventManager;
use \Meetingroom\Entity\Event\Event;

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
        $event = new Event($id);
        $user = (new UserFactory())->getUser('Alex');
        $role = (new RoleFactory())->getRole($user, $event);
        var_dump($role);
        exit;
    }
}
