<?php

namespace Meetingroom\Render\View;

/**
 * Description of View
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
class View implements RenderableInterface
{
    protected $params = [];
    
    public function __get($name)
    {
        return isset($this->params[$name]) ? $this->params[$name] : null;
    }
    
    public function __set($name, $value)
    {
        $this->params[$name] = $value;
    }
    
    public function getData()
    {
        return $this->params;
    }
}
