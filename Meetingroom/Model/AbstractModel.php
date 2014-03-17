<?php

namespace Meetingroom\Model;

abstract class AbstractModel
{
    protected $db;

    final function __construct()
    {
        $this->db = \Phalcon\DI::getDefault()->getShared('db');
        $this->init();
    }
    
    protected function init()
    {
        
    }
}
