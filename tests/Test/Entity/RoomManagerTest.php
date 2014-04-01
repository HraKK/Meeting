<?php

namespace Test\Entity;

use \Meetingroom\Entity\Room\RoomManager;

class TestRoomManager extends RoomManager
{
    protected $roomModel;
    protected $roomEntity;
    
    public function setRoomModel($model)
    {
        $this->roomModel = $model;
    }
    
    public function setRoomEntity($entity)
    {
        $this->roomEntity = $entity;
    }
    
    public function getRoomEntity()
    {
        return $this->roomEntity;
    }
}
/**
 * Description of RoomManagerTest
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
class RoomManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider isRoomExistProvider
     */
    public function testRoomExist($id, $return, $expect)
    {
        $model = $this->getMockBuilder('\Meetingroom\Model\Room\RoomModel')
            ->disableOriginalConstructor()
            ->setMethods(['read'])
            ->getMock();
        
        $model->expects($this->once())
            ->method('read')
            ->with($id)
            ->will($this->returnValue($return));
        
        $manager = new TestRoomManager();
        $manager->setRoomModel($model);
        $this->assertEquals($expect, $manager->isRoomExist($id));
    }
    
    public function isRoomExistProvider()
    {
        return [
            [1, false, false],
            [2, true, true]
       ];
    }
    
    /**
     * @dataProvider getAllProvider
     */
    public function testGetAll($data, $room)
    {
        $model = $this->getMockBuilder('\Meetingroom\Model\Room\RoomModel')
            ->disableOriginalConstructor()
            ->setMethods(['getAll'])
            ->getMock();
        
        $model->expects($this->once())
            ->method('getAll')
            ->will($this->returnValue($data));
        
        $entity = $this->getMockBuilder('\Meetingroom\Model\Room\RoomEntity')
            ->disableOriginalConstructor()
            ->setMethods(['bind'])
            ->getMock();
        
        $entity->expects($this->exactly(3))
            ->method('bind')
            ->will($this->returnValue($room));
        
        $manager = new TestRoomManager();
        $manager->setRoomModel($model);
        $manager->setRoomEntity($entity);
        $all = $manager->getAll();
        $this->assertCount(3, $all);
    }
    
    public function getAllProvider()
    {
        return [
            [[['first'],['second'],['third']], 'room']
       ];
    }
    
    public function testGetEntity()
    {
        $manager = new RoomManager();
        $this->assertInstanceOf('\Meetingroom\Entity\Room\RoomEntity', $manager->getRoomEntity());
        $this->assertInstanceOf('\Meetingroom\Model\Room\RoomModel', $manager->getRoomModel());
    }
}
