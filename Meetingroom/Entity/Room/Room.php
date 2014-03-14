<?php
namespace Meetingroom\Entity\Room;


class Room
{

    protected $id;
    protected $fields = [
        'title',
        'description',
        'attendees'
    ];
    protected $loaded = false;

    public function __construct($id)
    {

    }

    public function bind($data)
    {
        foreach ($fields as $field) {

        }
    }

    public function save()
    {

    }


}