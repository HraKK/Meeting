<?php

namespace Test\Model;

use Meetingroom\Model\User\UserModel;

class TestUserModel extends UserModel
{
    public function setDB($db)
    {
        $this->db = $db;
    }
    
    public function setTable($table)
    {
        $this->table = $table;
    }
    
    public function setFields($fields)
    {
        $this->fields = $fields;
    }
}

/**
 * Description of UserModelTest
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
class UserModelTest extends \PHPUnit_Framework_TestCase
{
    public function testGetIdByUsername1()
    {
        $username = 'barif';
        $sql = "SELECT id FROM users WHERE nickname = ? LIMIT 1";
        $userModel = new TestUserModel();
        
        $db = $this->getMockBuilder('\Phalcon\Db')
            ->disableOriginalConstructor()
            ->setMethods(['query', 'numRows'])
            ->getMock();
        
        $db->expects($this->once())
            ->method('query')
            ->with($sql, [$username])
            ->will($this->returnSelf());
        
        $db->expects($this->once())
            ->method('numRows')
            ->will($this->returnValue(0));
        
        $userModel->setDB($db);
        $this->assertFalse($userModel->getIdByUsername($username));
    }
        
    public function testGetIdByUsername2()
    {
        $username = 'barif';
        $id = 1;
        $sql = "SELECT id FROM users WHERE nickname = ? LIMIT 1";
        $object = new \stdClass();
        $object->id = $id;
        
        $userModel = new TestUserModel();
        
        $db = $this->getMockBuilder('\Phalcon\Db')
            ->disableOriginalConstructor()
            ->setMethods(['query', 'numRows', 'fetch'])
            ->getMock();
        
        $db->expects($this->once())
            ->method('query')
            ->with($sql, [$username])
            ->will($this->returnSelf());
        
        $db->expects($this->once())
            ->method('numRows')
            ->will($this->returnValue(1));
        
        $db->expects($this->once())
            ->method('fetch')
            ->will($this->returnValue($object));
        
        $userModel->setDB($db);
        $this->assertEquals($id, $userModel->getIdByUsername($username));
    }
}