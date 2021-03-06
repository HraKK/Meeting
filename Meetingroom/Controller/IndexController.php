<?php

namespace Meetingroom\Controller;

use \Meetingroom\Render\Engine\HTMLTemplateEngine;
use \Meetingroom\Render\View\TemplateView;

class IndexController extends AbstractController
{
    public function indexAction()
    {
        if (!$this->isAllowed('index', 'index')) {
            return $this->onDenied();
        }
        
        $this->view->currectUsername = $this->session->get("username");
        $engine = new HTMLTemplateEngine();
        $view = new TemplateView($this->view);
        
        return $this->render->process($view, $engine);
    }
}
