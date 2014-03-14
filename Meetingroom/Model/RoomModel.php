<?php

namespace Meetingroom\Model;

class RoomModel extends AbstractModel
{

    public function getAll()
    {
        $result = $this->db->query("SELECT * FROM rooms ");
        $result->setFetchMode(\Phalcon\Db::FETCH_OBJ);

        return $result->fetchAll();
    }

}
