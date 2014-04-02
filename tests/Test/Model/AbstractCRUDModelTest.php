<?php

namespace Test\Model;

use Meetingroom\Model\AbstractCRUDModel;

class TestCRUDModel extends AbstractCRUDModel
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
 * Description of AbstractCRUDModelTest
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
class AbstractCRUDModelTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate1()
    {
        $table = 'table'; 
        $values = ['id' => 1, 'f1' => 123]; 
        $fields = ['id' => 'id', 'f1' => 'f1']; 
        $result = ['id' => 1];
        
        $crud = new TestCRUDModel();
        
        $db = $this->getMockBuilder('\Phalcon\Db')
            ->disableOriginalConstructor()
            ->setMethods(['insert'])
            ->getMock();
        
        $db->expects($this->once())
            ->method('insert')
            ->with($table, array_values($values), array_keys($values))
            ->will($this->returnValue($result));
        
        $crud->setDB($db);
        $crud->setTable($table);
        $crud->setFields($fields);
        $this->assertEquals($result['id'], $crud->create($values));
    }
    
    public function testCreate2()
    {
        $crud = new TestCRUDModel();
        $this->assertFalse($crud->create([]));
        $this->assertFalse($crud->read(0));
        $this->assertFalse($crud->update(0, []));
        $this->assertFalse($crud->delete(0));
    }
    
    public function testCreate3()
    {
        $table = 'table'; 
        $values = ['id' => 1, 'f1' => 123]; 
        $fields = ['id' => 'id', 'f1' => 'f1']; 
        $result = ['id' => false];
        
        $crud = new TestCRUDModel();
        
        $db = $this->getMockBuilder('\Phalcon\Db')
            ->disableOriginalConstructor()
            ->setMethods(['insert'])
            ->getMock();
        
        $db->expects($this->once())
            ->method('insert')
            ->with($table, array_values($values), array_keys($values))
            ->will($this->returnValue($result));
        
        $crud->setDB($db);
        $crud->setTable($table);
        $crud->setFields($fields);
        $this->assertFalse($result['id'], $crud->create($values));
    }
    
}
