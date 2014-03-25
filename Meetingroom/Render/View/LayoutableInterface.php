<?php

namespace Meetingroom\Render\View;

/**
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
interface LayoutableInterface extends RenderableInterface
{
    public function getLayout();
}
