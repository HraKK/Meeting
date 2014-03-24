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
            $this->onDenied();
        }
        
        $roomManager = new RoomManager();
        $rooms = $roomManager->getAll();
        $roomsDTO = [];
        
        foreach ($rooms as $room) {
            $roomsDTO[] = $room->getDTO();
        }
        
        echo json_encode($roomsDTO); exit;
    }
}
