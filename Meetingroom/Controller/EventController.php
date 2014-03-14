<?php

namespace Meetingroom\Controller;

class EventController extends AbstractController
{
    public function indexAction()
    {
        echo "<h1>Events</h1>";
    }

    public function showAction($id = 0)
    {
        $event = new \Meetingroom\Entity\Event((int) $id);
        var_dump($event);
        exit;
    }
}
