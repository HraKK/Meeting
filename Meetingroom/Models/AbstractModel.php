<?php

namespace Meetingroom\Models;

class AbstractModel extends \Phalcon\Mvc\Model
{
    protected  $db;
    
    public function beforeExecuteRoute($dispatcher)
    {
        $this->db =  $this->getDI()->get('mydb_con');
    }
}