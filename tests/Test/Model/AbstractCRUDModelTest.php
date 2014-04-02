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
    
    public function testRead1()
    {
        $table = 'table'; 
        $id = 1;
        $sql = 'SELECT table, table FROM table WHERE id = ?';
        $fields = ['id' => 'id', 'f1' => 'f1']; 
        $fetch = true;
        
        $crud = new TestCRUDModel();
        
        $db = $this->getMockBuilder('\Phalcon\Db')
            ->disableOriginalConstructor()
            ->setMethods(['escapeIdentifier', 'fetchOne'])
            ->getMock();
        
        $db->expects($this->any())
            ->method('escapeIdentifier')
            ->will($this->returnValue($table));
        
        $db->expects($this->any())
            ->method('fetchOne')
            ->with($sql, \Phalcon\Db::FETCH_ASSOC, [$id])
            ->will($this->returnValue($fetch));
        
        $crud->setDB($db);
        $crud->setTable($table);
        $crud->setFields($fields);
        $this->assertEquals($fetch, $crud->read($id));
    }
    
    public function testUpdate1()
    {
        $table = 'table'; 
        $id = 1;
        $fields = ['id' => 'id', 'f1' => 'f1']; 
        $values = ['id' => 1, 'f1' => 1];
        
        $crud = new TestCRUDModel();
        
        $db = $this->getMockBuilder('\Phalcon\Db')
            ->disableOriginalConstructor()
            ->setMethods(['update'])
            ->getMock();
        
        $db->expects($this->once())
            ->method('update')
            ->with($table, array_keys($values), array_values($values), "id = "  . $id)
            ->will($this->returnValue(true));
        
        $crud->setDB($db);
        $crud->setTable($table);
        $crud->setFields($fields);
        $this->assertTrue($crud->update($id, $values));
    }
    
    public function testDelete1()
    {
        $table = 'table'; 
        $id = 1;
        
        $crud = new TestCRUDModel();
        
        $db = $this->getMockBuilder('\Phalcon\Db')
            ->disableOriginalConstructor()
            ->setMethods(['delete'])
            ->getMock();
        
        $db->expects($this->once())
            ->method('delete')
            ->with($table, "id = " . $id)
            ->will($this->returnValue(true));
        
        $crud->setDB($db);
        $crud->setTable($table);
        $this->assertTrue($crud->delete($id));
    }
    
    public function testGetNextId()
    {
        $table = 'table'; 
        $id = 1;
        $sql = "select nextval('table_id_seq'::regclass)";
        $object = new \stdClass();
        $object->nextval = $id;
        
        $crud = new TestCRUDModel();
        
        $db = $this->getMockBuilder('\Phalcon\Db')
            ->disableOriginalConstructor()
            ->setMethods(['query', 'fetch'])
            ->getMock();
        
        $db->expects($this->once())
            ->method('query')
            ->with($sql)
            ->will($this->returnSelf());
        
        $db->expects($this->once())
            ->method('fetch')
            ->will($this->returnValue($object));
        
        $crud->setDB($db);
        $crud->setTable($table);
        $this->assertEquals($id, $crud->getNextId());
    }
}
