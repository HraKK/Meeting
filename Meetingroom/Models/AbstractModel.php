<?php

namespace Meetingroom\Models;

class AbstractModel extends \Phalcon\Mvc\Model
{
    protected  $db;
    
    public function onConstruct()
    {
        $this->db = $this->getDI()->getShared('mydb_con');
    }
}