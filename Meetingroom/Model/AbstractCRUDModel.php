<?php

namespace Meetingroom\Model;

abstract class AbstractCRUDModel extends AbstractModel
{
    protected $table = null;
    
    public function create($values = [], $params = null) 
    {
        if($this->table === null or empty($values)) {
            return false;
        }
        
        return $this->db->insert(
            $this->table,
            array_values($values),
            array_keys($values)
        );
    }
    
    public function read($id = null) 
    {
        if($this->table === null or $id === null) {
            return false;
        }
        
        $sql = sprintf(
            'SELECT * FROM %s WHERE id = ?',
            $this->db->escapeIdentifier($this->table)
        );
        
        return $this->db->fetchOne($sql, \Phalcon\Db::FETCH_ASSOC, [$id]);
    }
    
    public function update($id = null, $values = []) 
    {
        if($this->table === null or $id === null or empty($values)) {
            return false;
        }
        
        return $this->db->update(
            $this->table,
            array_keys($values),
            array_values($values),
            "id = " . (int) $id
        );
    }
    
    public function delete($id = null) 
    {
        if($this->table === null or $id === null) {
            return false;
        }
        
        return $this->db->delete(
            $this->table,
            "id = " . (int) $id
        );
    }
}