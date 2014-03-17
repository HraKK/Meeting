<?php

namespace Meetingroom\Entity\Room;

/**
 * Class RoomManager
 * @package Meetingroom\Entity\Room
 */
class RoomManager
{
    /**
     * @var \Meetingroom\Model\RoomModel
     */
    protected $roomModel;

    /**
     * lazy initialization RoomModel
     * @return \Meetingroom\Model\RoomModel
     */
    public function getRoomModel()
    {
        if ($this->roomModel == null) {
            $this->roomModel = new \Meetingroom\Model\RoomModel();
        }

        return $this->roomModel;
    }

    /**
     * lazy initialization RoomModel
     * @return \Meetingroom\Model\RoomModel
     */
    public function isRoomExist($id)
    {
        $room = $this->getRoomModel()->read($id);
        
        return $room ? true : false;
    }

    /**
     * Array of Room entity objects
     * @return array
     */
    public function getAll()
    {
        $rooms = $this->getRoomModel()->getAll();
        $roomsObj = [];
        foreach ($rooms as $room) {
            $roomsObj[] = (new RoomEntity())->bind($room);
        }

        return $roomsObj;
    }

}
