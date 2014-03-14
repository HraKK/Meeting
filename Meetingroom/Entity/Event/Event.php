<?php

namespace Meetingroom\Entity\Event;

use \Meetingroom\Entity\OwnableInterface;
use \Meetingroom\Entity\Exception\FieldNotExistException;

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

    public function __get($name)
    {
        $this->fieldExist($name);

        if ($this->loaded === false) {
            $this->load();
        }

        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->fieldExist($name);
        $this->$name = $value;
        return $this;
    }

    public function fieldExist($name)
    {
        $map = array_flip($this->fields);

        if (!in_array($name, $map)) {
            throw new FieldNotExistException(sprintf('Field with name %s not exist in class %s', $name, __CLASS__));
        }
    }

    protected function load()
    {
        $model = new \Meetingroom\Model\EventModel();
        $this->bind($model->getEventData($this->id));
    }

    public function bind($data = [])
    {
        if (empty($data)) {
            return $this;
        }

        $this->loaded = true;

        foreach ($this->fields as $db => $map) {
            if(!isset($data[$db])) {
                continue;
            }
            
            $this->$map = $data[$db];
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
