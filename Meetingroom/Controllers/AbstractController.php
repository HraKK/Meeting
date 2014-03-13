<?php

namespace Meetingroom\Controllers;

abstract class AbstractController extends \Phalcon\Mvc\Controller
{
    protected  $db;
    
    abstract public function indexAction();
    
    public function beforeExecuteRoute($dispatcher)
    {
        $this->db = \Phalcon\DI::getDefault()->get('mydb_con');
    }
}