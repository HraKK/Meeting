<?php

namespace Test;

use Phalcon\DI,
    \Phalcon\Test\UnitTestCase as PhalconTestCase;

abstract class UnitTestCase extends PhalconTestCase
{

    /**
     * @var \Voice\Cache
     */
    protected $_cache;

    /**
     * @var \Phalcon\Config
     */
    protected $_config;

    /**
     * @var bool
     */
    private $_loaded = false;

    public function setUp(Phalcon\DiInterface $di = null, Phalcon\Config $config = null)
    {

        // Загрузка дополнительных сервисов, которые могут потребоваться во время тестирования
        $di = DI::getDefault();

        // получаем любые компоненты DI, если у вас есть настройки, не забудьте передать их родителю

        parent::setUp($di);

        $this->_loaded = true;
    }

    /**
     * Проверка на то, что тест правильно настроен
     * @throws \PHPUnit_Framework_IncompleteTestError;
     */
    public function __destruct()
    {
        if (!$this->_loaded) {
            throw new \PHPUnit_Framework_IncompleteTestError('Please run parent::setUp().');
        }
    }
}