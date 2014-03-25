<?php

namespace Meetingroom\View;

use \Meetingroom\View\Engine\EnginableInterface;
use \Meetingroom\View\RenderableInterface;

/**
 * Description of Render
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
class Render
{
    public function process(RenderableInterface $view, EnginableInterface $engine)
    {
        echo $engine->render($view);
    }
}
