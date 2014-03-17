<?php

namespace Meetingroom\Model;

abstract class AbstractModel
{
    protected $db;

    final function __construct()
    {
        $this->db = \Phalcon\DI::getDefault()->getShared('db');
        $this->init(func_get_args());
    }
    
    protected function init(array $args)
    {
        
    }
}
