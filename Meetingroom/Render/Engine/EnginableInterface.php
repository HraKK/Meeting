<?php

namespace Meetingroom\Render\Engine;

use Meetingroom\Render\View\RenderableInterface;

/**
 * Description of EnginableInterface
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
interface EnginableInterface
{
    public function render(RenderableInterface $view);
}
