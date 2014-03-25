<?php

namespace Meetingroom\View\Engine;

use \Meetingroom\View\RenderableInterface;

/**
 * Description of HTMLTemplateEngine
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
class HTMLTemplateEngine extends AbstractEngine
{
    public function render(RenderableInterface $view)
    {
        $dir = $view->getViewsDir();
        extract($view->getParamsToView(), EXTR_SKIP);
        ob_start();
        $filename = $dir . $this->layer;
        if(is_file($filename)) {
            include $filename;
        }
        $content = ob_get_contents();
        ob_end_clean();
        
        return $content;
    }
}