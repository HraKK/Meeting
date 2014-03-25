<?php

namespace Meetingroom\Controller;

use \Meetingroom\Render\Engine\HTMLTemplateEngine;
use \Meetingroom\Render\View\ViewWithTemplate;

class IndexController extends AbstractController
{
    public function indexAction()
    {
        $engine = new HTMLTemplateEngine();
        $view = new ViewWithTemplate($this->view);
        
        return $this->render->process($view, $engine);
    }
}
