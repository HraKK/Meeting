<?php

class User extends \Phalcon\Mvc\Model
{
    public function test() 
    {
        $con = \Phalcon\DI::getDefault()->get('mydb_con');
        $result = $con->query("SELECT * FROM users ORDER BY name");
        $result->setFetchMode(Phalcon\Db::FETCH_OBJ);
        while ($robot = $result->fetch()) {
            echo $robot->name;
        }
    }
    
}