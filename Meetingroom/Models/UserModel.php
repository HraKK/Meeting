<?php

namespace Meetingroom\Models;

class UserModel extends AbstractModel
{
    public function getId($username) 
    {
        $result = $this->db->query("SELECT * FROM users WHERE name = ? LIMIT 1", [$username]);
        $result->setFetchMode(\Phalcon\Db::FETCH_OBJ);
        while ($robot = $result->fetch()) {
            return $robot->id;
        } 
        
        return false;
    }

}
