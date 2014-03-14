<?php
namespace Meetingroom\Bootstrapper;

interface BootstrapperInterface
{
    public function bootstrap(\Phalcon\Mvc\Application $application);
} 