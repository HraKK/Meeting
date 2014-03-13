<?php

namespace Meetingroom\Models;

class User extends \Phalcon\Mvc\Model
{
    public function test() 
    {
        $result = $this->db->query("SELECT * FROM users ORDER BY name");
        $result->setFetchMode(\Phalcon\Db::FETCH_OBJ);
        while ($robot = $result->fetch()) {
            echo $robot->name;
        }
    }
    
}