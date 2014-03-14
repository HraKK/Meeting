<?php

namespace Meetingroom\Entity\Event;

use Meetingroom\Entity\OwnableInterface;

class Event extends \Meetingroom\Entity\AbstractEntity implements OwnableInterface
{
    protected $loaded = false;
    
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
        'rooom_id' => 'roomId',
        'date_start' => 'dateStart',
        'date_end' => 'dateEnd',
        'user_id' => 'userId',
        'title' => 'title',
        'description' => 'desription',
        'repeatable' => 'repeatable',
        'attendees' => 'attendees'
    ];

    public function __construct($id = null)
    {
        $this->id = $id;
        if ($id !== null) {
            $this->load();
        }

        return $this;
    }

    protected function load()
    {
        $model = new \Meetingroom\Model\EventModel();
        $data = $model->getEventData($this->id);
        if ($data) {
            $this->loaded = true;
            $this->userId = $data->user_id;
        }

        return $this;
    }

    public function bind($data = [])
    {
        if (sizeof($data) === 0) {
            return $this;
        }

        $this->loaded = true;
        
        foreach ($this->fields as $db => $map) {
            $this->$map = isset($data[$db]) ? $data[$db] : null;
        }
        
        return $this;
    }

    public function isLoaded() 
    {
        return (bool) $this->loaded;
    }
    
    public function ownerId()
    {
        return $this->userId;
    }

}
