<?php

namespace Test\Entity;

use Meetingroom\Entity\User\AuthorizedEntity;

class ExtendedAuthorizedEntity extends AuthorizedEntity
{
    protected $userModel;
    
    public function setModel($model)
    {
        $this->userModel = $model;
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }
}

/**
 * Description of AuthorizedEntityTest
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
class AuthorizedEntityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider userProvider
     */
    public function testAuthorizedEntity($username)
    {
        $model = $this->getMockBuilder('\Meetingroom\Model\AbstractModel')
            ->disableOriginalConstructor()
            ->setMethods(['getIdByUsername'])
            ->getMock();
        
        $model->expects($this->once())
            ->method('getIdByUsername')
            ->with($username)
            ->will($this->returnValue(1));
        
        $userEntity = new ExtendedAuthorizedEntity();
        $userEntity->setModel($model);
        $this->assertSame($userEntity, $userEntity->loadByUsername($username));
        $this->assertEquals(1, $userEntity->getId());
    }
    
    public function userProvider()
    {
        return [
            [
                'Barif'
            ]
        ];
    }
}
