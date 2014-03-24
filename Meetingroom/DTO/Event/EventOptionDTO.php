<?php

namespace Meetingroom\DTO\Event;

/**
 * Description of EventOptionDTO
 *
 * @author Alexandr Gureev <barif@syneforge.com>
 */
class EventOptionDTO extends \Meetingroom\DTO\AbstractDTO
{
    public $repeated_on = [];
    
    public function __construct(array $properties)
    {
        $map = [
            'mon' => 0,
            'tue' => 1,
            'wed' => 2,
            'thu' => 3,
            'fri' => 4,
            'sat' => 5,
            'sun' => 6,
        ];
        
        foreach ($properties as $field => $value) {
            if($field == 'id') {
                continue;
            }
            if($value == true) {
                $this->repeated_on[] = $map[$field];
            }
        }
    }
} 