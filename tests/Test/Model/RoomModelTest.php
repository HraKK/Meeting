<?php

namespace Test\Model;

use Meetingroom\Model\Room\RoomModel;

class TestRoomModel extends RoomModel
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
 * Description of RoomModelTest
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
class RoomModelTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAll()
    {
        $sql = "SELECT id, title, description, attendees FROM rooms ";
        $roomModel = new TestRoomModel();
        
        $db = $this->getMockBuilder('\Phalcon\Db')
            ->disableOriginalConstructor()
            ->setMethods(['query', 'fetchAll', 'setFetchMode'])
            ->getMock();
        
        $db->expects($this->once())
            ->method('query')
            ->with($sql)
            ->will($this->returnSelf());
        
        $db->expects($this->once())
            ->method('setFetchMode')
            ->with(\Phalcon\Db::FETCH_ASSOC)
            ->will($this->returnSelf());
        
        $db->expects($this->once())
            ->method('fetchAll')
            ->will($this->returnValue(true));
        
        $roomModel->setDB($db);
        $this->assertTrue($roomModel->getAll());
    }
}