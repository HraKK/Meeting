<?php

namespace Meetingroom\Render;

use \Meetingroom\Render\Engine\EnginableInterface;
use \Meetingroom\Render\View\RenderableInterface;

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
