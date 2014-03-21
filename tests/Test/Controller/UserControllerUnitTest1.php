<?php
namespace Test\Controller;

class UnitTest extends \UnitTestCase
{
    public function testTestCase()
    {
        $c = new \Meetingroom\Controller\UserController();
        $c->indexAction();
    }
    
    public function testUser()
    {
        $data = Users::find();
        $this->assertFalse(empty($data->toArray()));
    }


    public function testController()
    {
        $a = new IndexController();
        $this->assertEquals('1', $a->testAction());
    }
    
//    use \Phalcon\DI as DI;
//
//class AuthTest extends \UnitTestCase
//{
    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testPasswordHash()
    {
        $security = $this->di->get( 'security' );
        $cryptedPassword = $security->hash( 'password' );

        $this->assertTrue(
            $security->checkHash(
                'password',
                $cryptedPassword
            ));
    }

    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testSession()
    {
        $session = $this->di->get( 'session' );

        $this->assertFalse( $session->start() );
        $this->assertTrue( $session->isStarted() );

        $session->set( 'some', 'value' );
        $this->assertEquals(
            $session->get( 'some' ),
            'value' );
        $this->assertTrue( $session->has( 'some' ) );
        $this->assertEquals(
            $session->get( 'undefined', 'my-default' ),
            'my-default' );
    }

    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testRandomToken()
    {
        $action = new \Actions\Users\Auth();
        $token1 = $action->generateRandomToken();
        $this->assertTrue( strlen( $token1 ) == 40 );

        $token2 = $action->generateRandomToken();
        $this->assertFalse( $token1 == $token2 );
    }

    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testCreateDestroyToken()
    {
        $action = new \Actions\Users\Auth();
        $token = $action->createToken( 1, TRUE );
        $this->assertTrue( strlen( $token ) > 0 );
        $this->assertTrue( $action->destroyToken( 1 ) );
    }

    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testLoginWithToken()
    {
        $action = new \Actions\Users\Auth();
        $token = $action->createToken( 1, TRUE );
        $this->assertTrue( strlen( $token ) > 0 );
        
        $user = $action->authorizeToken();
        $this->assertTrue( $user != FALSE );
        $this->assertObjectHasAttribute( 'id', $user );
        $this->assertTrue( valid( $user->id ) );
        $this->assertTrue( $action->destroyToken( 1 ) );
    }

    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testNoLoginParams()
    {
        $params = array();
        $action = new \Actions\Users\Auth();
        $util = $this->di->get( 'util' );

        $this->assertFalse( $action->login( $params ) );
        $this->assertCount( 3, $util->getMessages() );
    }

    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testBadLoginParams()
    {
        $util = $this->di->get( 'util' );
        $action = new \Actions\Users\Auth();
        $params = array(
            'email' => 'not an email',
            'password' => 'password' );

        $this->assertCount( 0, $util->getMessages() );
        $this->assertFalse( $action->login( $params ) );
        $this->assertCount( 1, $util->getMessages() );
    }

    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testNonExistingLoginEmail()
    {
        $util = $this->di->get( 'util' );
        $action = new \Actions\Users\Auth();
        $params = array(
            'email' => 'missing@example.org',
            'password' => 'password' );

        $this->assertCount( 0, $util->getMessages() );
        $this->assertFalse( $action->login( $params ) );
        $this->assertCount( 1, $util->getMessages() );
    }

    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testBadLoginCredentials()
    {
        $util = $this->di->get( 'util' );
        $action = new \Actions\Users\Auth();
        $params = array(
            'email' => 'test@example.org',
            'password' => 'incorrect' );

        $this->assertCount( 0, $util->getMessages() );
        $this->assertFalse( $action->login( $params ) );
        $this->assertCount( 1, $util->getMessages() );
    }

    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testCorrectLoginCredentials()
    {
        $util = $this->di->get( 'util' );
        $action = new \Actions\Users\Auth();
        $params = array(
            'email' => 'test@example.org',
            'password' => 'password' );

        $this->assertCount( 0, $util->getMessages() );
        $this->assertTrue( $action->login( $params ) );
        $this->assertCount( 0, $util->getMessages() );

        $session = $this->di->get( 'session' );
        $this->assertTrue( valid( $session->get( 'user_id' ) ) );
    }

    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testDestroyCookieSession()
    {
        $action = new \Actions\Users\Auth();
        $this->assertTrue( $action->destroyToken( 1 ) );
        $this->assertTrue( $action->destroySession() );

        $session = $this->di->get( 'session' );
        $this->assertFalse( valid( $session->get( 'user_id' ) ) );
    }
    
    /**
     * @group library
     * @group auth
     */
    public function testInit()
    {
        $this->assertFalse( $this->di->get( 'auth' )->init() );
    }

    /**
     * @group library
     * @group auth
     */
//    public function testBadManualLoading()
//    {
//        try
//        {
//            $this->di->get( 'auth' )->load( NULL );
//        }
//        catch ( \Base\Exception $expected )
//        {
//            return;
//        }
//
//        $this->fail( "Invalid auth test exception wasn't raised." );
//    }

    /**
     * @group library
     * @group auth
     */
//    public function testManualLoading()
//    {
//        $auth = $this->di->get( 'auth' );
//        $auth->load( 1 );
//
//        $this->assertTrue( is_array( $auth->getUser() ) );
//    }
    
    /**
     * @group library
     * @group auth
     */
//    public function testInit()
//    {
//        $this->assertFalse( $this->di->get( 'auth' )->init() );
//    }

    /**
     * @group library
     * @group auth
     */
    public function testBadManualLoading()
    {
        try
        {
            $this->di->get( 'auth' )->load( NULL );
        }
        catch ( \Base\Exception $expected )
        {
            return;
        }

        $this->fail( "Invalid auth test exception wasn't raised." );
    }

    /**
     * @group library
     * @group auth
     */
    public function testManualLoading()
    {
        $auth = $this->di->get( 'auth' );
        $auth->load( 1 );

        $this->assertTrue( is_array( $auth->getUser() ) );
    }
    
     /**
     * @group library
     * @group util
     */
    public function testRemoveMessage()
    {
        $this->assertCount( 0, $this->di->get( 'util' )->getMessages() );
    }

    /**
     * @group library
     * @group util
     */
    public function testAddMessage()
    {
        $util = $this->di->get( 'util' );
        $util->addMessage( 'test message', SUCCESS );
        $this->assertCount( 1, $util->getMessages() );
    }

    /**
     * @group library
     * @group util
     */
    public function testBenchmarks()
    {
        $util = $this->di->get( 'util' );
        $util->startBenchmark();
        usleep( 25 );
        $util->stopBenchmark();
        $debugInfo = $util->getDebugInfo();

        $this->assertCount( 8, $debugInfo );
        $this->assertArrayHasKey( 'memory', $debugInfo );
        $this->assertArrayHasKey( 'time', $debugInfo );
        $this->assertGreaterThan( 0, $debugInfo[ 'memory' ] );
        $this->assertGreaterThan( 0, $debugInfo[ 'time' ] );

        $util->resetBenchmarks();
        $debugInfo = $util->getDebugInfo();

        $this->assertEquals( 0, $debugInfo[ 'memory' ] );
        $this->assertEquals( 0, $debugInfo[ 'time' ] );
    }
    
    /**
     * @group library
     * @group validate
     */
    public function testEmail()
    {
        $validate = $this->di->get( 'validate' );
        $util = $this->di->get( 'util' );
        $params = array(
            'email' => 'not an email' );
        $validate->add(
            'email',
            array(
                'email' => array()
            ));

        $this->assertFalse( $validate->run( $params ) );
        $this->assertCount( 1, $util->getMessages() );

        $params = array(
            'email' => 'test@example.org' );
        $validate->add(
            'email',
            array(
                'email' => array()
            ));

        $util->clearMessages();
        $this->assertTrue( $validate->run( $params ) );
        $this->assertCount( 0, $util->getMessages() );
    }

    /**
     * @group library
     * @group validate
     */
    public function testExists()
    {
        $validate = $this->di->get( 'validate' );
        $util = $this->di->get( 'util' );
        $params = array(
            'password' => 'password' );
        $validate->add(
            'missing',
            array(
                'exists' => array()
            ));

        $this->assertFalse( $validate->run( $params ) );
        $this->assertCount( 1, $util->getMessages() );

        $validate->add(
            'password',
            array(
                'exists' => array()
            ));

        $util->clearMessages();
        $this->assertTrue( $validate->run( $params ) );
        $this->assertCount( 0, $util->getMessages() );
    }

    /**
     * @group library
     * @group validate
     */
    public function testLength()
    {
        $validate = $this->di->get( 'validate' );
        $util = $this->di->get( 'util' );
        $params = array(
            'password' => 'abc' );
        $validate->add(
            'password',
            array(
                'length' => array(
                    'min' => 6 )
            ));

        $this->assertFalse( $validate->run( $params ) );
        $this->assertCount( 1, $util->getMessages() );

        $params = array(
            'password' => 'password1234' );
        $validate->add(
            'password',
            array(
                'length' => array(
                    'min' => 6 )
            ));

        $util->clearMessages();
        $this->assertTrue( $validate->run( $params ) );
        $this->assertCount( 0, $util->getMessages() );
    }

    /**
     * @group library
     * @group validate
     */
    public function testInvalidType()
    {
        $validate = $this->di->get( 'validate' );

        try
        {
            $validate->add(
                'test',
                array(
                    'missing' => array()
                ));
        }
        catch ( \Base\Exception $expected )
        {
            return;
        }

        $this->fail( "Invalid validation test exception wasn't raised." );
    }
    
    
}
 