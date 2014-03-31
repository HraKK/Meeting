<?php

namespace Test\DTO;

/**
 * Description of EventOptionDTOTest
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
class EventOptionDTOTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dtoProvider
     */
    public function testConstructor(array $dataSet, array $dataCheck, array $wrongData)
    {
        
        $dto = new \Meetingroom\DTO\Event\EventOptionDTO($dataSet);
        $this->assertEquals($dataCheck, $dto->getRepeatedOn());
        $this->assertNotEquals($wrongData, $dto->getRepeatedOn());
    }
    
    public function dtoProvider()
    {
        return [
            [
                [   'id'  => 213,
                    'mon' => true,
                    'tue' => true,
                    'wed' => false,
                    'thu' => null,
                    'fri' => 0,
                    'sat' => 123,
                    'sun' => 'true'
                ], 
                [0, 1],
                [0, 1, 5, 6]
            ], [
                [
                    'mon' => false,
                    'tue' => true,
                    'wed' => false,
                    'thu' => true,
                ], 
                [1, 3],
                [1, 3, 4, 5, 6]
            ]
        ];
    }
}
