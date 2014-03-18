<?php

namespace Meetingroom\Entity\Event;

use \Meetingroom\Entity\OwnableInterface;

class EventEntity extends \Meetingroom\Entity\AbstractEntity implements OwnableInterface
{
    protected $modelName = 'EventModel';
    
    protected $id = null;
    protected $roomId = null;
    protected $dateStart = null;
    protected $dateEnd = null;
    protected $userId = null;
    protected $title = null;
    protected $desription = null;
    protected $repeatable = null;
    protected $attendees = null;
    
    protected $fields = [
        'id' => 'id',
        'room_id' => 'roomId',
        'date_start' => 'dateStart',
        'date_end' => 'dateEnd',
        'user_id' => 'userId',
        'title' => 'title',
        'description' => 'desription',
        'repeatable' => 'repeatable',
        'attendees' => 'attendees'
    ];

    public function ownerId()
    {
        return $this->userId;
    }
}
