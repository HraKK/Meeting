<?php

namespace Meetingroom\Model;

class EventModel extends AbstractModel
{
    public function eventExist($id) 
    {
        $result = $this->db->query("SELECT id FROM events WHERE id = ? LIMIT 1", [$id]);
        return $result->numRows() === 0 ? false : true;
    }
    
    public function getEventData($id) 
    {
        $result = $this->db->query("SELECT * FROM events WHERE id = ? LIMIT 1", [$id]);
        $result->setFetchMode(\Phalcon\Db::FETCH_OBJ);
        return $result->numRows() === 0 ? false : $result->fetch();
    }
    
    public function getUserIdByEventId($id) 
    {
        $result = $this->db->query("SELECT user_id FROM events WHERE id = ? LIMIT 1", [$id]);
        $result->setFetchMode(\Phalcon\Db::FETCH_OBJ);
        return $result->numRows() === 0 ? false : $result->fetch()->user_id;
    }
}
