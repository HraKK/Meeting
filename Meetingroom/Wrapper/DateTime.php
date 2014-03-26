<?php
namespace Meetingroom\Wrapper;

/**
 * Class DateTime
 * @package Meetingroom\Wrapper
 * @author Denis Maximovskikh <denkin.syneforge.com>
 */
class DateTime extends \DateTime
{

    function __toString()
    {
        return $this->format("Y-m-d H:i:s");
    }


} 