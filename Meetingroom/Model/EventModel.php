<?php

namespace Meetingroom\Model;

class EventModel extends AbstractCRUDModel
{
    protected $table = 'events';
    
    public function init(array $args)
    {
        $this->fields = array_shift($args);
    }
    
    public function eventExist($id) 
    {
        $result = $this->db->query("SELECT id FROM events WHERE id = ? LIMIT 1", [$id]);
        return $result->numRows() === 0 ? false : true;
    }
    
    public function getEventData($id) 
    {
        $result = $this->db->query("SELECT * FROM events WHERE id = ? LIMIT 1", [$id]);
        $result->setFetchMode(\Phalcon\Db::FETCH_OBJ);
        return $result->numRows() === 0 ? [] : $result->fetch();
    }
    
    public function getUserIdByEventId($id) 
    {
        $result = $this->db->query("SELECT user_id FROM events WHERE id = ? LIMIT 1", [$id]);
        $result->setFetchMode(\Phalcon\Db::FETCH_OBJ);
        return $result->numRows() === 0 ? false : $result->fetch()->user_id;
    }
    
    public function getActiveEvents() 
    {
        $result = $this->db->query("SELECT * FROM events");
        $result->setFetchMode(\Phalcon\Db::FETCH_ASSOC);
        
        $list = [];
        
        if($result->numRows() === 0) {
            return $list;
        }
        
        while ($event = $result->fetch()) {
            $list[$event['id']] = $event;
        }
        
        return $list;
    }
}
