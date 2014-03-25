<?php

namespace Meetingroom\Controller;

use \Meetingroom\Entity\Room\RoomManager;
use \Meetingroom\View\Engine\JSONEngine;
use \Meetingroom\View\Render;

class RoomController extends AbstractController
{
    public function indexAction()
    {
        
    }
    
    public function readAction()
    {
        if (!$this->isAllowed('room', 'read')) {
            $this->onDenied();
        }
        
        $roomManager = new RoomManager();
        $rooms = $roomManager->getAll();
        $roomsDTO = [];
        
        foreach ($rooms as $room) {
            $roomsDTO[] = $room->getDTO();
        }
        
        $this->view->rooms = $roomsDTO;
        
        $engine = new JSONEngine();
        $render = new Render();
        
        return $render->process($this->view, $engine);
    }
}
