<?php

namespace Meetingroom\Model\Event;

class EventOptionModel extends \Meetingroom\Model\AbstractCRUDModel
{
    protected $table = 'repeating_options';
    protected $fields = ['id', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];

}
