<?php

namespace Meetingroom\View;

/**
 * Description of View
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
class View implements RenderableInterface
{
    protected $params = [];
    protected $viewsDir;
    
    public function __construct()
    {
        
    }
    
    public function __get($name)
    {
        return isset($this->params[$name]) ? $this->params[$name] : null;
    }
    
    public function __set($name, $value)
    {
        $this->params[$name] = $value;
    }
    
    public function getParamsToView()
    {
        return $this->params;
    }
    
    public function setViewsDir($dir)
    {
        $this->viewsDir = $dir;
    }
    
    public function getViewsDir()
    {
        return $this->viewsDir;
    }
}
