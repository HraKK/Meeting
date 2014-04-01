<?php

namespace Meetingroom\DTO\Event;

use Meetingroom\Entity\Event\EventEntity;
/**
 * Class EventDTO
 * @package Meetingroom\DTO\Event
 * @author Denis Maximovskikh <denkin.syneforge.com>
 */
class EventDTO extends \Meetingroom\DTO\AbstractDTO
{
    public $id;
    public $room_id;
    public $date_start;
    public $date_end;
    public $title;
    public $description;
    public $repeatable;
    public $attendees;
    public $repeated_on;
    public $owner;

    public function __construct(EventEntity $event)
    {
        $fields = $event->getFields();

        if (empty($fields) == true) {
            return;
        }

        foreach ($fields as $bd_field => $class_field) {
            switch ($class_field) {
                case 'dateStart':
                case 'dateEnd':
                    $this->$bd_field = strtotime($event->$class_field);
                    break;
                case 'userId':
                    $this->$bd_field = $event->$class_field;
                    $this->owner = $event->getOwner()->getNickname();
                    break;
                case 'repeatable':
                    $this->repeatable = $event->repeatable;
                    $this->repeated_on = $event->getRepeatables();
                    break;
                default:
                    $this->$bd_field = $event->$class_field;
                    break;
            }
        }
    }

}
