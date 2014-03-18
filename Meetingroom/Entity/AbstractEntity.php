<?php

namespace Meetingroom\Entity;

use \Meetingroom\Entity\Exception\FieldNotExistException;

abstract class AbstractEntity
{
    protected $loaded = false;
    protected $model = null;
    protected $modelName = null;
    protected $fields = [];

    /**
     * @param null|integer $id
     */
    public function getModel()
    {
        $class = '\Meetingroom\Model\\' . $this->modelName;
        return $this->model === null ? new $class : $this->model;
    }

    /**
     * @param null|integer $id
     */
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
        if (!key_exists($name, $map)) {
            throw new FieldNotExistException(sprintf('Field with name %s not exist in class %s', $name, __CLASS__));
        }
    }

    /**
     * Load data and bind data from db(RoomModel)
     *
     * @return void
     */
    protected function load()
    {
        $this->bind($this->getModel()->read($this->id));
    }
    
    /**
     * Link data from the db and class fields
     *
     * @param array $data
     * @return $this
     * @throws \Meetingroom\Entity\Exception\FieldNotExistException
     */
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

    /**
     * check is loaded data
     * @return bool
     */
    public function isLoaded()
    {
        return (bool) $this->loaded;
    }

    public function save()
    {
        $values = [];
        
        foreach ($this->fields as $db => $map) {
            $values[$db] = $this->$map;
        }
        
        $model = $this->getModel();
        return $this->id === null ? $model->create($values) : $model->update($this->id, $values);
    }
    
    public function delete()
    {
        return $this->getModel()->delete($this->id);
    }
}
