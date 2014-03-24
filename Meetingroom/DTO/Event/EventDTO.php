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
    public $roomId;
    public $dateStart;
    public $dateEnd;
    public $userId;
    public $title;
    public $desription;
    public $repeatable;
    public $attendees;
    public $repeatedOn;
    public $owner;
} 