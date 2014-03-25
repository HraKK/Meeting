<?php

namespace Meetingroom\Render\Engine;

use Meetingroom\Render\View\RenderableInterface;

/**
 * Description of JSONEngine
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
class JSONEngine implements EnginableInterface
{
    public function render(RenderableInterface $view)
    {
        return json_encode($view->getData());
    }

}
