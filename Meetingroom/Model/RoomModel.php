<?php

namespace Meetingroom\Model;

class RoomModel extends AbstractCRUDModel
{
    protected $table = 'rooms';
    protected $fields = ['id', 'title', 'description', 'attendees'];
    
    public function getAll()
    {
        $result = $this->db->query("SELECT id, title, description, attendees FROM rooms ");
        $result->setFetchMode(\Phalcon\Db::FETCH_ASSOC);

        return $result->fetchAll();
    }
}
