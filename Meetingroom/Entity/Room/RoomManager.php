<?php
namespace Meetingroom\Entity\Room;

class RoomManager
{

    public function getAll()
    {

        $roomModel = new \Meetingroom\Model\RoomModel();

        return $roomModel->getAll();
    }

}