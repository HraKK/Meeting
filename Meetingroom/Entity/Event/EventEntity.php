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

    protected function getProperties()
    {
        $fields_array = [];
        foreach ($this->fields as $bd_field => $class_field) {
            switch ($class_field) {
                case 'dateStart':
                case 'dateEnd':
                    $fields_array[$bd_field] = strtotime($this->$class_field);
                    break;
                case 'userId':
                    $fields_array[$bd_field] = $this->$class_field;
                    $owner = new AuthorizedEntity($this->$class_field);
                    $fields_array['owner'] = $owner->nickname;
                    break;
                case 'repeatable':
                    $fields_array['repeatable'] = $this->repeatable;
                    $fields_array['repeated_on'] = $this->getRepeatables();
                    break;
                default:
                    $fields_array[$bd_field] = $this->$class_field;
                    break;
            }
            
        }
        return $fields_array;
    }
    
    protected function getOptionsModel()
    {
        return ($this->optionsModel = $this->optionsModel === null ? new EventOptionEntity($this->id) : $this->optionsModel);
    }

    protected function getRepeatables() 
    {
        if($this->repeatable == false) {
            return [];
        }
        
        $options = $this->getOptionsModel()->getDTO();
        return $options->repeated_on;
    }
}
