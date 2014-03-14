<?php

namespace Meetingroom\Controller;

use \Meetingroom\Entity\Event\EventFactory;
use \Meetingroom\Entity\Role\RoleFactory;
use \Meetingroom\Entity\User\UserFactory;

class EventController extends AbstractController
{
    public function indexAction()
    {
        echo "<h1>Events</h1>";
    }

    public function showAction($id = 0)
    {
        $event = (new EventFactory())->getEvent($id);
        $user = (new UserFactory())->getUser('Alex');
        $role = (new RoleFactory())->getRole($user, $event);
        var_dump($role);
        exit;
    }
}
