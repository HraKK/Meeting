<?php

namespace Meetingroom\Entity\Event;

class EventOptionEntity extends \Meetingroom\Entity\AbstractEntity
{
    protected $modelName = 'EventOptionModel';
    
    protected $id = null;
    protected $mon = null;
    protected $tue = null;
    protected $wed = null;
    protected $thu = null;
    protected $fri = null;
    protected $sat = null;
    protected $sun = null;
    
    protected $fields = ['id' => 'id', 'mon' => 'mon', 'tue' => 'tue', 'wed' => 'wed', 
        'thu' => 'thu', 'fri' => 'fri', 'sat' => 'sat', 'sun' => 'sun'];
}
