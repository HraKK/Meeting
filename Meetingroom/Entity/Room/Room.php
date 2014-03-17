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

    /**
     * Link data from the db and class fields
     *
     * @param array $data
     * @return $this
     * @throws \Meetingroom\Entity\Exception\FieldNotExistException
     */
    public function bind(array $data)
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
        $this->loaded = true;
        return $this;
    }

    /**
     * Load data and bind data from db(RoomModel)
     *
     * @return void
     */
    public function load()
    {
        $roomModel = new \Meetingroom\Model\RoomModel();
        $roomInfo = $roomModel->read($this->id);
        $this->bind($roomInfo);
        $this->loaded = true;
    }


    /**
     * check is loaded data
     * @return bool
     */
    public function isLoaded()
    {
        return (bool)$this->loaded;
    }


    protected function fieldExist($field)
    {
        return property_exists(__CLASS__, $field);
    }

}