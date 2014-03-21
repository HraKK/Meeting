<?php

namespace Meetingroom\Validate;

/**
 * Description of ValidTimestampInterface
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
interface ValidableInterface
{
    public function isValid();
    public function getMessage();
}
