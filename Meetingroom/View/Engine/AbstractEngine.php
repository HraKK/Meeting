<?php

namespace Meetingroom\View\Engine;

/**
 * Description of AbstractEngine
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
abstract class AbstractEngine implements EnginableInterface
{
    protected $layer = 'index.php';

    public function getLayer()
    {
        return $this->layer;
    }
    
    public function setLayer($layer)
    {
        $this->layer = $layer;
    }
}
