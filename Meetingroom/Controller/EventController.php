<?php

namespace Meetingroom\Controller;

use \Meetingroom\Entity\Room\RoomManager;
use \Meetingroom\Entity\Event\EventOptionEntity;
use \Meetingroom\Entity\Event\EventEntity;
use \Meetingroom\Entity\Event\Lookupper\EventLookupper;
use \Meetingroom\Entity\Event\Lookupper\Criteria\DayPeriodCriteria;
use \Meetingroom\Entity\Event\Lookupper\Criteria\RoomCriteria;
use \Meetingroom\Entity\Event\Lookupper\Criteria\WeekPeriodCriteria;
use \Meetingroom\Validate\Timestamp\Timestamp;
use \Meetingroom\Validate\Timestamp\TimestampCompare;
use Phalcon\Validation\Validator\Regex as RegexValidator;

class EventController extends AbstractController
{

    public function initialize()
    {
        parent::initialize();

        $this->validator = new \Phalcon\Validation();

        $this->validator->add(
            'room_id',
            new RegexValidator(array(
                'pattern' => '/^\d*$/',
                'message' => 'Room id must be integer'
            ))
        );
        $this->validator->setFilters('room_id', 'int');
//        ..etc
    }

    /**
     * @example /event/test_validation?room_id=12aa23&aa=bb
     * @todo delete before stage
     */
    public function test_validationAction()
    {
        var_dump($this->getFormData()); //['room_id']
        die();
    }

    public function indexAction()
    {
        if (!$this->isAllowed('index', 'index')) {
            $this->onDenied();
        }

        $roomId = (int) $this->request->getPost("room_id", "int");
        $day = (int) $this->request->getPost("day", "int");
        $month = (int) $this->request->getPost("month", "int");
        $year = (int) $this->request->getPost("year", "int");
        $week = (int) $this->request->getPost("week", "int");

        $roomManager = new RoomManager();
        $rooms = $roomManager->getAll();

        $roomCriteria = new RoomCriteria($roomId);
        
        if($week == 1) {
            $periodCriteria = new WeekPeriodCriteria($day, $month, $year);
        } else {
            $periodCriteria = new DayPeriodCriteria($day, $month, $year);
        }
        
        $lookupper = new EventLookupper($this->di);

        $events = $lookupper
            ->setPeriodCriteria($periodCriteria)
            ->setRoomCriteria($roomCriteria)
            ->setFields(['id', 'title', 'date_start', 'date_end', 'description', 'user_id', 'room_id', 'repeatable', 'attendees'])
            ->lookup();

        $eventsDTO = [];
        foreach ($events as $event) {
            $eventsDTO[] = $event->getDTO();
        }

        $this->sendOutput(['success' => true, 'events' => $eventsDTO]);
    }
    
    public function createAction()
    {
        if(!$this->isAllowed('event', 'create')) {
            $this->onDenied();
        }
        
        $title = $this->request->getPost("title", "striptags");
        $roomId = $this->request->getPost("room_id", "int");
        $isRepeatable = $this->request->getPost("repeatable", "int");
        $dateStart = $this->request->getPost("date_start", "string");
        $dateEnd = $this->request->getPost("date_end", "string");
        $description = $this->request->getPost("description", "striptags");
        $attendees = $this->request->getPost("attendees", "int");
        
        $mon = $this->request->getPost("mon", "int");
        $tue = $this->request->getPost("tue", "int");
        $wed = $this->request->getPost("wed", "int");
        $thu = $this->request->getPost("thu", "int");
        $fri = $this->request->getPost("fri", "int");
        $sat = $this->request->getPost("sat", "int");
        $sun = $this->request->getPost("sun", "int");
        
        if(strlen($title) < 3) {
            die('Title should be longer');
        }
        
        $roomManager = new RoomManager();
        if(!$roomManager->isRoomExist($roomId)) {
            $this->sendError('room ain`t exist');
        }

        $lookupper = new EventLookupper($this->di);
        $event = new EventEntity();

        $start = strtotime($dateStart);
        $end = strtotime($dateEnd);

        if ($start === false || $end === false || $end <= $start) {
            $this->sendError('wrong date');
        }
        
        $event->bind([
            'title' => $title,
            'room_id' => $roomId,
            'user_id' => $this->user->id,
                'date_start' => $start,
                'date_end' => $end,
                'description' => $description,
                'repeatable' => $isRepeatable,
            'attendees' => $attendees
        ]);
        
        $option = new EventOptionEntity();
        
        if($isRepeatable) {
            $option->bind([
                'id' => $event->id,
                'mon' => $mon,
                'tue' => $tue,
                'wed' => $wed,
                'thu' => $thu,
                'fri' => $fri,
                'sat' => $sat,
                'sun' => $sun,
            ]);
        }
        
        $conflict = $lookupper->checkIsConflict($event, $option);
        
        if(!$conflict) {
            $eventId = $event->save();
            if(!$eventId){
                $this->sendError('event not created');
            }
            
            if($isRepeatable) {
                $option->bind(['id' => $event->id])->insert();
            }

            $this->sendOutput(['success' => true]);
        } else {
            $this->sendError('event conflict with other events');
            exit;
        }
    }
    
    public function updateAction()
    {
        $event = $this->getEventByRequest();
        
        $role = $this->getRoleFactory()->getRoleInEvent($this->user, $event);
        if(!$this->isAllowed('event', 'update', $role)) {
            $this->onDenied();
        }
        
        $title = $this->request->getPost("title", "striptags");
        $roomId = $this->request->getPost("room_id", "int");
        $isRepeatable = $this->request->getPost("repeatable", "int");
        $dateStart = $this->request->getPost("date_start", "string");
        $dateEnd = $this->request->getPost("date_end", "string");
        $description = $this->request->getPost("description", "striptags");
        $attendees = $this->request->getPost("attendees", "int");
        
        $mon = $this->request->getPost("mon", "int");
        $tue = $this->request->getPost("tue", "int");
        $wed = $this->request->getPost("wed", "int");
        $thu = $this->request->getPost("thu", "int");
        $fri = $this->request->getPost("fri", "int");
        $sat = $this->request->getPost("sat", "int");
        $sun = $this->request->getPost("sun", "int");
        
        if(strlen($title) < 3) {
            $this->sendError('Title should be longer');
        }
        
        if($roomId !== $event->roomId) {
            $roomManager = new RoomManager();
            if(!$roomManager->isRoomExist($roomId)) {
                $this->sendError('room ain`t exist');
            }
        }
         
        $lookupper = new EventLookupper($this->di);

        $start = strtotime($dateStart);
        $end = strtotime($dateEnd);

        if ($start === false || $end === false || $end <= $start) {
            $this->sendError('Wrong date');
        }
        
        $event->bind([
            'title' => $title,
            'room_id' => $roomId,
                'date_start' => $start,
                'date_end' => $end,
                'description' => $description,
                'repeatable' => $isRepeatable,
            'attendees' => $attendees
        ]);
        
        $option = new EventOptionEntity();
        
        if($isRepeatable) {
            $option->bind([
                'id' => $event->id,
                'mon' => $mon,
                'tue' => $tue,
                'wed' => $wed,
                'thu' => $thu,
                'fri' => $fri,
                'sat' => $sat,
                'sun' => $sun
            ]);
        }
        
        $conflict = $lookupper->checkIsConflict($event, $option);
        
        if(!$conflict) {
            $eventId = $event->save();
            
            if($isRepeatable) {
                $option->update();
            }

            $this->sendOutput(['success' => true]);
        } else {
            $this->sendError('event conflict with other events');
            exit;
        }
    }
    
    public function deleteAction()
    {
        $event = $this->getEventByRequest();
        
        $role = $this->getRoleFactory()->getRoleInEvent($this->user, $event);
        if(!$this->isAllowed('event', 'delete', $role)) {
            $this->onDenied();
        }

        $event->delete() ? $this->sendOutput(['success' => true]) : $this->sendError('false');
    }
    
    protected function getEventByRequest()
    {
        $eventId = $this->request->getPost("event_id", "int");
        
        $event = new EventEntity($eventId);
        if($event->isLoaded() === false) {
            $this->sendError('event not found');
        }
        
        return $event;
    }
}
