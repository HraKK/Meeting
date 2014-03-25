<?php

namespace Meetingroom\Controller;

use \Meetingroom\Entity\Room\RoomManager;

class RoomController extends AbstractController
{
    public function indexAction()
    {
        
    }
    
    public function readAction()
    {
        if (!$this->isAllowed('room', 'read')) {
            return $this->onDenied();
        }
        
        $roomManager = new RoomManager();
        $rooms = $roomManager->getAll();
        $roomsDTO = [];
        
        foreach ($rooms as $room) {
            $roomsDTO[] = $room->getDTO();
        }
        
        $this->view->rooms = $roomsDTO;
        return $this->render();
    }
}
