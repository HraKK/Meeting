<?php

namespace Meetingroom\Render\Engine;

use \Meetingroom\Render\View\RenderableInterface;

/**
 * Description of HTMLTemplateEngine
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
class HTMLTemplateEngine implements EnginableInterface
{
    public function render(RenderableInterface $view)
    {
        extract($view->getData(), EXTR_SKIP);
        ob_start();
        if (is_file($view->getLayout())) {
            include $view->getLayout();
        }
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

}
