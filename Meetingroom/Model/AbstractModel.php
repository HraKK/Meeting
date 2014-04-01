<?php

namespace Meetingroom\Model;

abstract class AbstractModel
{
    protected $db;
    protected $table = null;
    protected $fields = [];
    
    final function __construct()
    {
        $di = \Phalcon\DI::getDefault();
        if(is_object($di)) {
            $this->db = $di->getShared('db');
        }
        
        $this->init();
    }
    
    protected function init()
    {
        
    }
}
