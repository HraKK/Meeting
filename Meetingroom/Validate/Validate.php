<?php

namespace Meetingroom\Validate;

/**
 * Description of ValidTimestampEntity
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
abstract class Validate implements ValidableInterface
{
    protected $status = false;
    protected $value;
    protected $errorMessage;
    
    protected function setMessage($msg)
    {
        $this->errorMessage = $msg;
    }
    
    public function getMessage()
    {
        return $this->errorMessage;
    }
    
    public function getValue()
    {
        return $this->value;
    }
}
