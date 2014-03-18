<?php

namespace Meetingroom\Model;

class EventOptionModel extends AbstractCRUDModel
{
    protected $table = 'repeating_options';
    protected $fields = ['id', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
    
}
