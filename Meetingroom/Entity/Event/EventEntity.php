<?php

namespace Meetingroom\Entity\Event;

use \Meetingroom\Entity\OwnableInterface;
use \Meetingroom\Entity\Event\EventOptionEntity;
use \Meetingroom\Entity\User\AuthorizedEntity;

class EventEntity extends \Meetingroom\Entity\AbstractEntity implements OwnableInterface
{
    protected $modelName = '\Meetingroom\Model\Event\EventModel';
    protected $DTOName = '\Meetingroom\DTO\Event\EventDTO';

    protected $id = null;
    protected $roomId = null;
    protected $dateStart = null;
    protected $dateEnd = null;
    protected $userId = null;
    protected $title = null;
    protected $desription = null;
    protected $repeatable = null;
    protected $attendees = null;
    protected $optionsModel = null;
    protected $owner = null;
    
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

    public function getDTO()
    {
        return ($this->DTO = $this->DTO === null ? new $this->DTOName($this) : $this->DTO);
    }

    public function getOwnerId()
    {
        return $this->userId;
    }

    public function getOwner()
    {
        return ($this->userId != null) ? new AuthorizedEntity($this->userId) : new AuthorizedEntity() ;
    }
    
    public function getRepeatables() 
    {
        if($this->repeatable == false) {
            return [];
        }
        
        $optionsDTO = $this->getOptionsModel()->getDTO();
        return $optionsDTO->getRepeatedOn();
    }
    
    public function getOptionsModel()
    {
        return ($this->optionsModel = $this->optionsModel === null ? new EventOptionEntity($this->id) : $this->optionsModel);
    }
}
