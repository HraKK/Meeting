<?php
namespace Test;

class UnitTest extends \PHPUnit_Framework_TestCase
{

    public function testTestCase()
    {

        $this->assertEquals(
            'works',
            'works',
            'This is OK'
        );

        $this->assertEquals(
            'works',
            'works1',
            'This wil fail'
        );
    }

}
 