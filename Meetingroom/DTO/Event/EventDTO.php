<?php
namespace Meetingroom\DTO\Event;

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
} 