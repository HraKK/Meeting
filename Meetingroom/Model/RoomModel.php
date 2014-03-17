<?php

namespace Meetingroom\Model;

class RoomModel extends AbstractCRUDModel
{
    protected $table = 'rooms';

    public function getAll()
    {
        $result = $this->db->query("SELECT * FROM rooms ");
        $result->setFetchMode(\Phalcon\Db::FETCH_ASSOC);

        return $result->fetchAll();
    }

    public function getById($id)
    {
        $result = $this->db->query("SELECT * FROM rooms WHERE id = ? ", [$id]);
        $result->setFetchMode(\Phalcon\Db::FETCH_ASSOC);

        return $result->fetch();
    }


}
