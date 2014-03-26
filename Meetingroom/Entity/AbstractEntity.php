<?php

namespace Meetingroom\Entity;

use \Meetingroom\Entity\Exception\FieldNotExistException;

abstract class AbstractEntity
{
    protected $loaded = false;
    protected $model = null;
    protected $modelName = null;
    protected $DTO = null;
    protected $DTOName = null;
    protected $fields = [];

    public function getModel()
    {
        return $this->model === null ? new $this->modelName : $this->model;
    }

    public function getDTO()
    {
        return $this->DTO === null ? new $this->DTOName($this->getProperties()) : $this->DTO;
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
        if($name == 'fields') {
            return $this->fields;
        }
        
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

    protected function fieldExist($name)
    {
        if (!in_array($name, $this->fields)) {
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
        $this->setLoaded();
        $data = $this->getModel()->read($this->id);
        return ($data) ? $this->bind($data) : $this;
    }
    
    protected function setLoaded()
    {
        $this->loaded = true;
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
        $this->setLoaded();
        
        if (empty($data)) {
            return $this;
        }
        
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
        return $this->id == null ? $this->insert(): $this->update();
    }
    
    public function insert()
    {
        $values = $this->composeValues();
        $model = $this->getModel();
        $result = $model->create($values);
        $this->id  = ($result) ? $result : null;
        return $result;
    }
    
    public function update()
    {
        $values = $this->composeValues();
        $model = $this->getModel();
        return $model->update($this->id, $values);
    }
    
    protected function composeValues()
    {
        $values = [];
        
        foreach ($this->fields as $db => $map) {
            $values[$db] = (is_object($this->$map)) ? strval($this->$map) : $this->$map;
        }
        
        return $values;
    }
    
    public function delete()
    {
        return $this->getModel()->delete($this->id);
    }

    /**
     * Return entity data in array
     *
     * @return array fields with data
     * @author Denis Maximovskikh <denkin.syneforge.com>
     */
    protected function getProperties()
    {
        $fields_array = [];
        foreach ($this->fields as $bd_field => $class_field) {
                    $fields_array[$bd_field] = $this->$class_field;
            }
        return $fields_array;
    }

}
