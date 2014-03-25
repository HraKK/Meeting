<?php

namespace Meetingroom\Render\View;

/**
 * Description of ViewWithTemplate
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
class ViewWithTemplate implements LayoutableInterface, RenderableInterface
{
    protected $viewsDir;
    protected $layout = 'index';
    protected $affix = '.php';
    protected $view;
    
    public function __construct(RenderableInterface $view)
    {
        $this->view = $view;
        $this->viewsDir = __DIR__ . '/../../Resource/';
    }
    
    public function setViewsDir($dir)
    {
        $this->viewsDir = $dir;
    }

    public function getLayout()
    {
        return $this->viewsDir . $this->layout . $this->affix;
    }
    
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }
    
    public function setAffix($affix)
    {
        $this->affix = $affix;
    }
    
    public function __get($name)
    {
        return $this->view->$name;
    }
    
    public function __set($name, $value)
    {
        $this->view->$name = $value;
    }
    
    public function getData()
    {
        return $this->view->getData();
    }
}
