<?php

namespace Meetingroom\Model;

abstract class AbstractCRUDModel extends AbstractModel
{
    public function create(array $values) 
    {
        if($this->table === null || empty($values)) {
            return false;
        }
        
        $insert = $this->performInsert($values);
        
        $result = $this->db->insert(
            $this->table,
            array_values($insert),
            array_keys($insert)
        );

        return $result;
    }
    
    protected function performInsert($values)
    {
        $insert = [];
        
        foreach ($this->fields as $key) {
            if($key == 'id') {
                continue;
            }
            
            $insert[$key] = isset($values[$key]) ? $values[$key] : null;
        }
        
        return $insert;
    }
    
    public function read($id) 
    {
        if($this->table === null || empty($id) || empty($this->fields)) {
            return false;
        }
        
        $connection = $this->db;
        $select = implode(', ', array_map(function($item) use ($connection) {
            return $connection->escapeIdentifier($item);
        }, $this->fields));

        $sql = sprintf(
            'SELECT ' . $select . ' FROM %s WHERE id = ?',
            $this->db->escapeIdentifier($this->table)
        );
        
        return $this->db->fetchOne($sql, \Phalcon\Db::FETCH_ASSOC, [$id]);
    }
    
    public function update($id, $values) 
    {
        if($this->table === null || empty($id) || empty($values)) {
            return false;
        }
        
        return $this->db->update(
            $this->table,
            array_keys($values),
            array_values($values),
            "id = " . (int) $id
        );
    }
    
    public function delete($id) 
    {
        if($this->table === null || empty($id)) {
            return false;
        }
        
        return $this->db->delete(
            $this->table,
            "id = " . (int) $id
        );
    }
}