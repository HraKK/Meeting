<?php
/**
 * User: Sergey V. Kuzin <sergey@kuzin.name>
 * Date: 03.09.13
 * Time: 0:21
 */

class LockTest extends \Phalcon\Test\UnitTestCase
{
    public function testIsLockedFileBackend()
    {
        $backendOptionsFile = array(
            'lockDir' => '/www/smarty/var/run/',
            'adapter'   => 'file',
        );

        $lock = new \Peregrin\Lock($backendOptionsFile);
        $this->assertTrue($lock->lock());
        $checkLock = new \Peregrin\Lock($backendOptionsFile);
        $this->assertTrue($checkLock->isLocked());
        $lock->unlock();
    }

    /**
     * @expectedException \Peregrin\Lock\Exception
     */
    public function testLockFileBackend()
    {
        $backendOptionsFile = array(
            'lockDir' => '/www/smarty/var/run/',
            'adapter'   => 'file',
        );

        $lock = new \Peregrin\Lock($backendOptionsFile);
        $this->assertTrue($lock->lock());
        $checkLock = new \Peregrin\Lock($backendOptionsFile);
        $this->assertFalse($checkLock->lock());
    }
/*
    public function testWhiteFileBackend()
    {
        $backendOptionsFile = array(
            'cacheDir' => '/www/smarty/var/run/',
            'adapter'   => 'File',
        );

        $lock = new \Peregrin\Lock($backendOptionsFile);
        $this->assertTrue($lock->lock());
        $checkLock = new \Peregrin\Lock($backendOptionsFile);
        $start = time();
        $this->assertTrue($checkLock->lock(null, 2));
        $this->assertEquals(time() - $start, 2);
    }
*/
    public function testIsLockedMemcacheBackend()
    {
        $backendOptions = array(
            'host' => 'localhost',
            'port' => 11211,
            'persistent' => false,
            'prefix'=> 'lock.',
            'adapter' => 'Memcache',
        );

        $lock = new \Peregrin\Lock($backendOptions);
        $this->assertTrue($lock->lock());
        $checkLock = new \Peregrin\Lock($backendOptions);
        $this->assertTrue($checkLock->isLocked());
    }

    /**
     * @expectedException \Peregrin\Lock\Exception
     */
    public function testLockMemcacheBackend()
    {
        $backendOptions = array(
            'host' => 'localhost',
            'port' => 11211,
            'persistent' => false,
            'prefix'=> 'lock.',
            'adapter' => 'Memcache',
        );

        $lock = new \Peregrin\Lock($backendOptions);
        $this->assertTrue($lock->lock());
        $checkLock = new \Peregrin\Lock($backendOptions);
        $this->assertFalse($checkLock->lock());
    }
}