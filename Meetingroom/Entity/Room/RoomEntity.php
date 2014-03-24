<?php
namespace Meetingroom\Entity\Room;

class RoomEntity extends \Meetingroom\Entity\AbstractEntity
{
    protected $modelName = '\Meetingroom\Model\Room\RoomModel';
    protected $DTOName = '\Meetingroom\DTO\Room\RoomDTO';

    protected $id;
    protected $title;
    protected $description;
    protected $attendees;
    
    protected $fields = [
        'id' => 'id',
        'title' => 'title',
        'description' => 'description',
        'attendees' => 'attendees'
    ];
}