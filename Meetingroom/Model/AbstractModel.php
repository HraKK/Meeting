<?php

namespace Meetingroom\Model;

abstract class AbstractModel extends \Phalcon\Mvc\Model
{
    protected $db;

    public function onConstruct()
    {
        $this->db = $this->getDI()->getShared('db');
    }

}
