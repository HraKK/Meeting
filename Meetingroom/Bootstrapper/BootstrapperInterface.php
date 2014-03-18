<?php
namespace Meetingroom\Bootstrapper;

/**
 * Interface BootstrapperInterface
 * @package Meetingroom\Bootstrapper
 * @author Denis Maximovskikh <denkin.syneforge.com>
 */
interface BootstrapperInterface
{
    public function bootstrap(\Phalcon\Mvc\Application $application);
} 