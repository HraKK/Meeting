<?php

namespace Meetingroom\View\Engine;

use Meetingroom\View\RenderableInterface;

/**
 * Description of JSONEngine
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
class JSONEngine extends AbstractEngine
{
    public function render(RenderableInterface $view)
    {
        return json_encode($view->getParamsToView());
    }
}