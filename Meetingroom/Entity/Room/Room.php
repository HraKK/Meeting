<?php
namespace Meetingroom\Entity\Room;


class Room extends \Meetingroom\Entity\AbstractEntity
{

    protected $id;
    protected $title;
    protected $description;
    protected $attendees;

    protected $loaded = false;

    /**
     * @param null|integer $id
     */
    public function __construct($id = null)
    {
        $this->id = $id;
        if ($id !== null) {
            $this->load();
        }
    }


    public function __get($name)
    {
        $this->fieldExist($name);

        if ($this->loaded === false) {
            $this->load();
        }

        return $this->$name;
    }


    public function bind($data)
    {
        foreach ($data as $field => $value) {
            if ($this->fieldExist($field)) {
                $this->$field = $value;
            } else {
                throw new \Meetingroom\Entity\Exception\FieldNotExistException(sprintf(
                    'Field with name %s not exist in class %s',
                    $field,
                    __CLASS__
                ));
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


    public function fieldExist($field)
    {
        return property_exists(__CLASS__, $field);
    }


    public function save()
    {

    }
}