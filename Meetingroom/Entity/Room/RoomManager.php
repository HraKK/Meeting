<?php

namespace Meetingroom\Entity\Room;

/**
 * Class RoomManager
 * @package Meetingroom\Entity\Room
 */
class RoomManager
{
    /**
     * @var \Meetingroom\Model\Room\RoomModel
     */
    protected $roomModel;

    /**
     * @var \Meetingroom\Entity\Room\RoomEntity
     */
    protected $roomEntity;

    
    /**
     * lazy initialization RoomModel
     * @return \Meetingroom\Model\Room\RoomModel
     */
    public function getRoomModel()
    {
        if ($this->roomModel == null) {
            $this->roomModel = new \Meetingroom\Model\Room\RoomModel();
        }

        return $this->roomModel;
    }

    /**
     * lazy initialization RoomModel
     * @return \Meetingroom\Model\Room\RoomModel
     */
    public function getRoomEntity()
    {
        if ($this->roomEntity == null) {
            $this->roomEntity = new \Meetingroom\Entity\Room\RoomEntity();
        }

        return $this->roomEntity;
    }
    
    /**
     * lazy initialization RoomModel
     * @return \Meetingroom\Model\Room\RoomModel
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
            $roomEntity = new \Meetingroom\Entity\Room\RoomEntity();
            $roomsObj[] = $roomEntity->bind($room);
        }

        return $roomsObj;
    }

}
