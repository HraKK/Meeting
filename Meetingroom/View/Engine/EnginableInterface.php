<?php

namespace Meetingroom\View\Engine;

use Meetingroom\View\RenderableInterface;

/**
 * Description of EnginableInterface
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
interface EnginableInterface
{
    public function render(RenderableInterface $view);
}
