<?php

class User extends \Phalcon\Mvc\Model
{
    public function test() 
    {
//        $con = $this->di->get('mydb_con');
        $con = \Phalcon\DI::getDefault()->get('mydb_con');
        $sql = "select * from users limit 1";
        $res = mysqli_query($con,$sql);
        $row = mysqli_fetch_assoc($res);
        echo '1'.$row['my_field'].'2';
    }
    
}