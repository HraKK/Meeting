<?php

namespace Meetingroom\Entities;

abstract class AbstractEntity
{
//    public function __construct() 
//    {
//        $this->init();
//    }
    
    abstract public function init($username);
}