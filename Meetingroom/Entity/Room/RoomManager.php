<?php
namespace Meetingroom\Entity\Room;

class RoomManager
{

    public function getAll()
    {

        $roomModel = new \Meetingroom\Model\RoomModel();
        $rooms = $roomModel->getAll();

        $roomsObj = [];
        foreach ($rooms as $room) {
            $roomsObj[] = (new Room())->bind($room);
        }

        return $roomsObj;
    }

}