<?php

namespace Test\Entity;

use Meetingroom\Entity\Event\EventEntity;

class ExtendedEventEntity extends EventEntity
{
    protected $optionsModel;
    
    public function __construct($id = null, $model = null)
    {
        $this->model = $model;
        parent::__construct($id);
    }
    public function setModel($model)
    {
        $this->model = $model;
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function setOptionsModel($mock)
    {
        $this->optionsModel = $mock;
    }
}

/**
 * Description of EventEntityTest
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
class EventEntityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider eventSingleProvider
     */
    public function testEventSingle($dataSet)
    {
        $model = $this->getMockBuilder('\Meetingroom\Model\AbstractModel')
            ->disableOriginalConstructor()
            ->setMethods(['read'])
            ->getMock();
        
        $model->expects($this->any())
            ->method('read');
        
        $eventEntity = new ExtendedEventEntity();
        $eventEntity->setModel($model);
        $eventEntity->bind($dataSet);
        $this->assertEmpty($eventEntity->getRepeatables());
        $this->assertEquals($dataSet['user_id'], $eventEntity->getOwnerId());
    }

    public function eventSingleProvider()
    {
        return [
            [
                [
                    'id' => 1,
                    'room_id' => 1,
                    'date_start' => '2014-01-01 00:00:00',
                    'date_end' => '2014-01-01 00:00:00',
                    'user_id' => 1,
                    'title' => 'SampleTitle',
                    'description' => 'SampleDescription',
                    'repeatable' => 0,
                    'attendees' => 42
                ]
            ]
        ];
    }
    
    /**
     * @dataProvider eventRepeatedProvider
     */
    public function testEventRepeated($dataSet)
    {
        $model = $this->getMockBuilder('\Meetingroom\Model\AbstractModel')
            ->disableOriginalConstructor()
            ->setMethods(['read'])
            ->getMock();
        
        $model->expects($this->any())
            ->method('read');
        
        
        $optionDTO = $this->getMockBuilder('\Meetingroom\DTO\Event\EventOptionDTO')
            ->disableOriginalConstructor()
            ->setMethods(['getRepeatedOn'])
            ->getMock();
        
        $optionDTO->expects($this->once())
            ->method('getRepeatedOn')
            ->will($this->returnValue(true));
        
        $optionModel = $this->getMockBuilder('\Meetingroom\Entity\Event\EventOptionEntity')
            ->disableOriginalConstructor()
            ->setMethods(['getDTO'])
            ->getMock();
        
        $optionModel->expects($this->once())
            ->method('getDTO')
            ->will($this->returnValue($optionDTO));
        
        $eventEntity = new ExtendedEventEntity();
        $eventEntity->setModel($model);
        $eventEntity->setOptionsModel($optionModel);
        $eventEntity->bind($dataSet);
        $this->assertTrue($eventEntity->getRepeatables());
    }

    public function eventRepeatedProvider()
    {
        return [
            [
                [
                    'id' => 1,
                    'room_id' => 1,
                    'date_start' => '2014-01-01 00:00:00',
                    'date_end' => '2014-01-01 00:00:00',
                    'user_id' => 1,
                    'title' => 'SampleTitle',
                    'description' => 'SampleDescription',
                    'repeatable' => 1,
                    'attendees' => 42
                ]
            ]
        ];
    }
    
    public function testEventOptionModel()
    {
        $eventEntity = new ExtendedEventEntity();
        $optionModel = $eventEntity->getOptionsModel();
        $this->assertInstanceOf('\Meetingroom\Entity\Event\EventOptionEntity', $optionModel);
        $owner = $eventEntity->getOwner();
        $this->assertInstanceOf('\Meetingroom\Entity\User\AuthorizedEntity', $owner);
        $this->assertNull($owner->getId());
    }
}
