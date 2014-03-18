<?php

namespace Meetingroom\Model;

class EventModel extends AbstractCRUDModel
{
    protected $table = 'events';
    protected $fields = [
        'id', 'room_id', 'date_start', 'date_end', 'user_id', 
        'title', 'description', 'repeatable', 'attendees'
    ];
    
    public function eventExist($id) 
    {
        $result = $this->db->query("SELECT id FROM events WHERE id = ? LIMIT 1", [$id]);
        return $result->numRows() === 0 ? false : true;
    }
    
    public function getUserIdByEventId($id) 
    {
        $result = $this->db->query("SELECT user_id FROM events WHERE id = ? LIMIT 1", [$id]);
        return $result->numRows() === 0 ? false : $result->fetch()->user_id;
    }
}
