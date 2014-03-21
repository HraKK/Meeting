<?php

namespace Meetingroom\Validate\Timestamp;

/**
 * Description of Timestamp
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
class Timestamp extends \Meetingroom\Validate\Validate
{
    protected $rawTimestamp;

    public function __construct($timestamp)
    {
        $this->rawTimestamp = $timestamp;
    }
    
    public function isValid()
    {
        try {
            $date = new \DateTime($this->rawTimestamp);
        } catch (\Exception $exc) {
            $this->setMessage('Timestamp format is Y-m-d H:i:s');
        }

        if(($date->format('Y-m-d H:i:s') != $this->rawTimestamp)) {
            $this->setMessage('Timestamp format is Y-m-d H:i:s');
        } else {
            $this->status = true;
            $this->value = $date->format('Y-m-d H:i:s');
        }
        
        return $this->status;
    }
}
