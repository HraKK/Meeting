<?php

namespace Test\Entity;

class AbstractEntity extends \Meetingroom\Entity\AbstractEntity
{
    protected $model = null;
    protected $fields = [];
    protected $id = null;
    protected $DTO = null;
    protected $DTOName = '\Test\Entity\DTO';
    
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

    public function setFields(array $fields)
    {
        $this->fields = $fields;
    }
    
    public function getProperties()
    {
        return parent::getProperties();
    }
    
    public function setDTO($dto)
    {
        $this->DTO = $dto;
    }

    public function setDTOName($dto)
    {
        $this->DTOName = $dto;
    }
}

class DTO extends \Meetingroom\DTO\AbstractDTO
{
}

/**
 * Description of AbstractEntityTest
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
class AbstractEntityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider abstractInsertProvider
     */
    public function testAbstractInsert($id, $fieldSet, $dataSet)
    {
        $model = $this->getMockBuilder('\Meetingroom\Model\AbstractModel')
            ->disableOriginalConstructor()
            ->setMethods(['read', 'create', 'update'])
            ->getMock();
        
        $model->expects($this->never())
            ->method('read');
        
        $model->expects($this->exactly(2))
            ->method('create')
            ->with($dataSet)
            ->will($this->returnValue($id));
        
        $model->expects($this->never())
            ->method('update');
        
        $abstractEntity = new AbstractEntity();
        $abstractEntity->setModel($model);
        $abstractEntity->setFields($fieldSet);
        $abstractEntity->fieldTwo = $dataSet['field_two'];
        $abstractEntity->bind($dataSet);
        $this->assertEquals($id, $abstractEntity->save());
        $this->assertEquals($id, $abstractEntity->insert());
    }

    public function abstractInsertProvider()
    {
        return [
            [
                1, ['field_one' => 'fieldOne', 'field_two' => 'fieldTwo', 'field_three' => 'fieldThree'],
                ['field_one' => 'fieldOneValue', 'field_two' => 'fieldTwoValue', 'field_three' => 'fieldThreeValue' ]
            ]
        ];
    }
    
    /**
     * @dataProvider abstractBindingProvider
     * @expectedException        \Meetingroom\Entity\Exception\FieldNotExistException
     * @expectedExceptionMessage Field with name WRONG_FIELD not exist in class Meetingroom\Entity\AbstractEntity
     */
    public function testAbstractBinding($fieldSet, $dataSet)
    {
        $abstractEntity = new AbstractEntity();
        $abstractEntity->setFields($fieldSet);
        
        /* Is fields set correctly? */
        $this->assertEquals($fieldSet, $abstractEntity->fields);
        
        /* Test loading flag */
        $this->assertFalse($abstractEntity->isLoaded());
        
        /* Bind should return $this and setup loaded true, anyway */
        $this->assertSame($abstractEntity, $abstractEntity->bind([]));
        $this->assertTrue($abstractEntity->isLoaded());
        
        /* Test binding with $dataSet*/
        $this->assertSame($abstractEntity, $abstractEntity->bind($dataSet));
        
        /* Check data linking */
        $this->assertEquals($dataSet['field_one'], $abstractEntity->fieldOne);
        
        /* Next assertion should be unequal*/
//        $this->assertNotEquals($dataSet['fieldOne'], $abstractEntity->fieldOne);
        
        $this->assertEquals($dataSet, $abstractEntity->getProperties());
        
        /* Test FieldNotExistException, this assertion should be last, because throwing exception */
        $abstractEntity->WRONG_FIELD;
    }

    public function abstractBindingProvider()
    {
        return [
            [
                ['field_one' => 'fieldOne', 'field_two' => 'fieldTwo', 'field_three' => 'fieldThree'],
                ['field_one' => 'fieldOneValue', 'field_two' => 'fieldTwoValue', 'field_three' => 'f3' ]
            ]
        ];
    }
    
    /**
     * @dataProvider abstractUpdateProvider
     */
    public function testAbstractUpdate($id, $fieldSet, $dataSet)
    {
        $model = $this->getMockBuilder('\Meetingroom\Model\AbstractModel')
            ->disableOriginalConstructor()
            ->setMethods(['read', 'update', 'insert'])
            ->getMock();
        
        $model->expects($this->any())
            ->method('read')
            ->with($id)
            ->will($this->returnValue($dataSet));
        
        $model->expects($this->any())
            ->method('update')
            ->with($id, $dataSet)
            ->will($this->returnValue($id));
        
        $model->expects($this->never())
            ->method('insert');
        
        $abstractEntity = new AbstractEntity();
        $abstractEntity->setId($id);
        $abstractEntity->setModel($model);
        $abstractEntity->setFields($fieldSet);
        $this->assertEquals($id, $abstractEntity->save());
        $this->assertEquals($id, $abstractEntity->update());
    }

    public function abstractUpdateProvider()
    {
        return [
            [
                1, ['field_one' => 'fieldOne', 'field_two' => 'fieldTwo', 'field_three' => 'fieldThree'],
                ['field_one' => 'fieldOneValue', 'field_two' => 'fieldTwoValue', 'field_three' => 'fieldThreeValue' ]
            ]
        ];
    }
    
    /**
     * @dataProvider abstractDeleteProvider
     */
    public function testAbstractDelete($id, $return)
    {
        $model = $this->getMockBuilder('\Meetingroom\Model\AbstractModel')
            ->disableOriginalConstructor()
            ->setMethods(['delete', 'read'])
            ->getMock();
        
        $model->expects($this->once())
            ->method('delete')
            ->with($id)
            ->will($this->returnValue($return));
        
        $model->expects($this->any())
            ->method('read')
            ->with($id)
            ->will($this->returnValue([]));
        
        $abstractEntity = new AbstractEntity($id, $model);
        $this->assertEquals($return, $abstractEntity->delete());
    }

    public function abstractDeleteProvider()
    {
        return [
            [ 1, false],
            [ 1, true]
        ];
    }
    
    
    /**
     * @dataProvider abstractBinding2Provider
     */
    public function testBind($fieldSet, $dataSet, $expectData)
    {
        $abstractEntity = new AbstractEntity();
        $abstractEntity->setFields($fieldSet);
        
        $abstractEntity->bind($dataSet);
        $abstractEntity->fieldThree = 'f3';
        
        $this->assertEquals($expectData, $abstractEntity->getProperties());
        $DTO = $abstractEntity->getDTO();
        $this->assertInstanceOf('\Test\Entity\DTO', $DTO);
        foreach ($fieldSet as $field => $value) {
            $this->assertEquals($expectData[$field], $DTO->$field);
        }
    }
    
    public function abstractBinding2Provider()
    {
        return [
            [
                ['field_one' => 'fieldOne', 'field_two' => 'fieldTwo', 'field_three' => 'fieldThree'],
                ['field_one' => 'fieldOneValue', 'field_two' => 'fieldTwoValue' ],
                ['field_one' => 'fieldOneValue', 'field_two' => 'fieldTwoValue', 'field_three' => 'f3' ]
            ]
        ];
    }
    
    /**
     * @dataProvider abstractDTOProvider
     */
    public function testDTO($fieldSet, $dataSet, $expectData)
    {
        $abstractEntity = new AbstractEntity();
        $abstractEntity->setFields($fieldSet);
        
        $abstractEntity->bind($dataSet);
        $abstractEntity->fieldThree = 'f3';
        
        $this->assertEquals($expectData, $abstractEntity->getProperties());
    }
    
    public function abstractDTOProvider()
    {
        return [
            [
                ['field_one' => 'fieldOne', 'field_two' => 'fieldTwo', 'field_three' => 'fieldThree'],
                ['field_one' => 'fieldOneValue', 'field_two' => 'fieldTwoValue' ],
                ['field_one' => 'fieldOneValue', 'field_two' => 'fieldTwoValue', 'field_three' => 'f3' ]
            ]
        ];
    }
    
}
