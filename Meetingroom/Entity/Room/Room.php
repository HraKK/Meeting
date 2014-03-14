<?php
namespace Meetingroom\Entity\Room;


class Room extends \Meetingroom\Entity\AbstractEntity
{

    protected $id;
    protected $title;
    protected $description;
    protected $attendees;

    protected $loaded = false;


    public function __construct($id = null)
    {
        $this->id = $id;
        if ($id !== null) {
            $this->load();
        }
    }


    public function bind($data)
    {
        foreach ($data as $field => $value) {
            if (property_exists(__CLASS__, $field)) {
                $this->$field = $value;
            } else {
                throw new \Exception('property not found');
            }
        }
        return $this;
    }

    public function load()
    {
        $roomModel = new \Meetingroom\Model\RoomModel();
        $roomInfo = $roomModel->getById($this->id);
        $this->bind($roomInfo);
        $this->loaded = true;
    }

    public function isLoaded()
    {
        return (bool)$this->loaded;
    }

    public function save()
    {

    }


}