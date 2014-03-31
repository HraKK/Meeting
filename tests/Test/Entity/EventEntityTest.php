<?php

namespace Test\Entity;

use Meetingroom\Entity\Event\EventEntity;

class ExtendedEventEntity extends EventEntity
{
    protected $authorizedEntity;
    
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
    
    public function getProperties()
    {
        return parent::getProperties();
    }
    
    public function getAuthorizedEntity()
    {
        return $this->authorizedEntity;
    }
    
    public function setAuthorizedEntity($entity)
    {
        $this->authorizedEntity = $entity;
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
     * @dataProvider abstractInsertProvider
     */
    public function testEvent($dataSet, $resultSet)
    {
        $model = $this->getMockBuilder('\Meetingroom\Model\AbstractModel')
            ->disableOriginalConstructor()
            ->setMethods(['read'])
            ->getMock();
        
        $model->expects($this->any())
            ->method('read');
        
        $entity = $this->getMockBuilder('\Meetingroom\Entity\User\AuthorizedEntity')
            ->disableOriginalConstructor()
            ->setMethods(['read'])
            ->getMock();
        
        $eventEntity = new ExtendedEventEntity();
        $eventEntity->setModel($model);
        $eventEntity->bind($dataSet);
        
        $eventEntity->id;
        $eventEntity->getProperties();
    }

    public function abstractInsertProvider()
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
                ],
                ['field_one' => 'fieldOneValue', 'field_two' => 'fieldTwoValue', 'field_three' => 'fieldThreeValue' ]
            ]
        ];
    }
}
