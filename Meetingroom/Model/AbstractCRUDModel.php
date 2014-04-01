<?php

namespace Meetingroom\Model;

abstract class AbstractCRUDModel extends AbstractModel
{
    public function create(array $values) 
    {
        if($this->getTable() === null || empty($values)) {
            return false;
        }
        
        $insert = $this->performInsert($values);
        
        $result = $this->getDB()->insert(
            $this->getTable(),
            array_values($insert),
            array_keys($insert)
        );

        return $result ? $insert['id'] : false;
    }
    
    protected function performInsert($values)
    {
        $insert = [];
        
        foreach ($this->getFields() as $key) {
            if($key == 'id') {
                $insert['id'] = isset($values[$key]) ? $values[$key] : $this->getNextId();
                continue;
            }
            
            $insert[$key] = isset($values[$key]) ? $values[$key] : null;
        }
        
        return $insert;
    }
    
    public function read($id) 
    {
        if($this->getTable() === null || empty($id) || empty($this->getFields())) {
            return false;
        }
        
        $connection = $this->getDB();
        $select = implode(', ', array_map(function($item) use ($connection) {
            return $connection->escapeIdentifier($item);
        }, $this->getFields()));

        $sql = sprintf(
            'SELECT ' . $select . ' FROM %s WHERE id = ?',
            $this->getDB()->escapeIdentifier($this->getTable())
        );
        
        return $this->getDB()->fetchOne($sql, \Phalcon\Db::FETCH_ASSOC, [$id]);
    }
    
    public function update($id, $values) 
    {
        if($this->getTable() === null || empty($id) || empty($values)) {
            return false;
        }
        
        return $this->getDB()->update(
            $this->getTable(),
            array_keys($values),
            array_values($values),
            "id = " . (int) $id
        );
    }
    
    public function delete($id) 
    {
        if($this->getTable() === null || empty($id)) {
            return false;
        }
        
        return $this->getDB()->delete(
            $this->getTable(),
            "id = " . (int) $id
        );
    }
    
    public function getNextId()
    {
        return (int) $this->getDB()->query("select nextval('" . $this->getTable() . "_id_seq'::regclass)")->fetch()->nextval;
    }
    
    public function getDB()
    {
        return $this->db;
    }
    
    public function getTable()
    {
        return $this->table;
    }
    
    public function getFields()
    {
        return $this->fields;
    }
}