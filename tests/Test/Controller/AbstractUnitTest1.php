<?php
namespace Test\Controller;

class AbstractController extends \UnitTestCase
{

    public function testIndexAction_shouldReturnMainView_whenCalledWithOutParams(){
        /** @var \Phalcon\DI\FactoryDefault $di */
        $di = $this->di;
        $this->assertNotNull($di);
        //var_dump($di->get('IndexController'));
        /** @var IndexController $ctrl */
        $ctrl = $di->get('session');

        $ctrl->has('ayut');
    }
}
 