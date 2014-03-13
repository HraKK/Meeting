<?php

namespace Meetingroom\Models;

class AbstractModel extends \Phalcon\Mvc\Model
{
    protected  $db;
    
    public function beforeExecuteRoute($dispatcher)
    {
        $this->db = \Phalcon\DI::getDefault()->get('mydb_con');
    }
}