<?php

namespace Meetingroom\Validate\Timestamp;

use \Meetingroom\Validate\ValidableInterface;

/**
 * Description of TimestampCompare
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
class TimestampCompare extends \Meetingroom\Validate\Validate 
{
    protected $dateStart;
    protected $dateEnd;

    public function __construct(ValidableInterface $dateStart, ValidableInterface $dateEnd)
    {
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
    }
    
    public function isValid()
    {
        if($this->dateStart->isValid() == false) {
            $this->setMessage('Date start is invalid');
            return $this->status;
        }
        
        if($this->dateEnd->isValid() == false) {
            $this->setMessage('Date end is invalid');
            return $this->status;
        }
        
        $dateStart = new \DateTime($this->dateStart->getValue());
        $dateEnd = new \DateTime($this->dateEnd->getValue());
            
        if($dateStart->format('Y-m-d') != $dateStart->format('Y-m-d')) {
            $this->setMessage('Event should start and end in same day');
            return $this->status;
        }
        
        if($dateStart>$dateEnd) {
            $this->setMessage('Date end should be greater than date start');
            return $this->status;
        }
        
        $this->status = true;
        return $this->status;
    }
}
