<?php

namespace Meetingroom\Controller;

use \Meetingroom\Entity\Room\RoomManager;
use \Meetingroom\Entity\Event\Lookupper\EventLookupper;
use \Meetingroom\Entity\Event\Lookupper\Criteria\RoomCriteria;
use \Meetingroom\Entity\Event\Lookupper\Criteria\DayPeriodCriteria;
use \Meetingroom\View\Engine\HTMLTemplateEngine;
use \Meetingroom\View\Render;

class IndexController extends AbstractController
{
    public function indexAction()
    {
        $engine = new HTMLTemplateEngine();
        $render = new Render();
        
        return $render->process($this->view, $engine);
    }
}
