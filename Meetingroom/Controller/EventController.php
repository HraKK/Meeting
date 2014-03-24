<?php

namespace Meetingroom\Controller;

use \Meetingroom\Entity\Room\RoomManager;
use \Meetingroom\Entity\Event\EventOptionEntity;
use \Meetingroom\Entity\Event\EventEntity;
use \Meetingroom\Entity\Event\Lookupper\EventLookupper;
use \Meetingroom\Entity\Event\Lookupper\Criteria\DayPeriodCriteria;
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

        $this->validator->setFilters("day", "int");
        $this->validator->setFilters("month", "int");
        $this->validator->setFilters("year", "int");

        $this->validator->setFilters('room_id', 'int');
        $this->validator->setFilters("title", "striptags");
        $this->validator->setFilters("repeatable", "int");
        $this->validator->setFilters("date_start", "string");
        $this->validator->setFilters("date_end", "string");
        $this->validator->setFilters("description", "striptags");
        $this->validator->setFilters("attendees", "int");

        $this->validator->setFilters("mon", "int");
        $this->validator->setFilters("tue", "int");
        $this->validator->setFilters("wed", "int");
        $this->validator->setFilters("thu", "int");
        $this->validator->setFilters("fri", "int");
        $this->validator->setFilters("sat", "int");
        $this->validator->setFilters("sun", "int");

        $this->formData = $this->getFormData(true);
    }

    /**
     * @example /event/test_validation?room_id=12aa23&aa=bb
     * @todo delete before stage
     */
    public function test_validationAction()
    {
        var_dump($this->formData);

        die();
    }

    public function indexAction()
    {
        if (!$this->isAllowed('index', 'index')) {
            $this->onDenied();
        }


        $roomManager = new RoomManager();
        $rooms = $roomManager->getAll();

        $roomCriteria = new RoomCriteria($id);
        $periodCriteria = new DayPeriodCriteria($this->formData->day, $this->formData->month, $this->formData->year);
        $lookupper = new EventLookupper($this->di);

        $events = $lookupper
            ->setPeriodCriteria($periodCriteria)
            ->setRoomCriteria($roomCriteria)
            ->setFields(['id', 'title'])
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


        if (strlen($this->formData->title) < 3) {
            die('Title should be longer');
        }
        
        $roomManager = new RoomManager();
        if (!$roomManager->isRoomExist($this->formData->roomId)) {
            $this->sendError('room ain`t exist');
        }

        $lookupper = new EventLookupper($this->di);
        $event = new EventEntity();

        $start = strtotime($this->formData->dateStart);
        $end = strtotime($this->formData->dateEnd);

        if ($start === false || $end === false || $end <= $start) {
            $this->sendError('wrong date');
        }
        
        $event->bind([
                'title' => $this->formData->title,
                'room_id' => $this->formData->roomId,
                'user_id' => $this->user->id,
                'date_start' => $start,
                'date_end' => $end,
                'description' => $this->formData->description,
                'repeatable' => $this->formData->isRepeatable,
                'attendees' => $this->formData->attendees
            ]);
        
        $option = new EventOptionEntity();

        if ($this->formData->isRepeatable) {
            $option->bind([
                'id' => $event->id,
                    'mon' => $this->formData->mon,
                    'tue' => $this->formData->tue,
                    'wed' => $this->formData->wed,
                    'thu' => $this->formData->thu,
                    'fri' => $this->formData->fri,
                    'sat' => $this->formData->sat,
                    'sun' => $this->formData->sun,
                ]);
        }
        
        $conflict = $lookupper->checkIsConflict($event, $option);
        
        if(!$conflict) {
            $eventId = $event->save();
            if(!$eventId){
                $this->sendError('event not created');
            }

            if ($this->formData->isRepeatable) {
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

        if (strlen($this->formData->title) < 3) {
            $this->sendError('Title should be longer');
        }

        if ($this->formData->roomId !== $event->roomId) {
            $roomManager = new RoomManager();
            if (!$roomManager->isRoomExist($this->formData->roomId)) {
                $this->sendError('room ain`t exist');
            }
        }
         
        $lookupper = new EventLookupper($this->di);

        $start = strtotime($this->formData->dateStart);
        $end = strtotime($this->formData->dateEnd);

        if ($start === false || $end === false || $end <= $start) {
            $this->sendError('Wrong date');
        }
        
        $event->bind([
                'title' => $this->formData->title,
                'room_id' => $this->formData->roomId,
                'date_start' => $start,
                'date_end' => $end,
                'description' => $this->formData->description,
                'repeatable' => $this->formData->isRepeatable,
                'attendees' => $this->formData->attendees
            ]);
        
        $option = new EventOptionEntity();

        if ($this->formData->isRepeatable) {
            $option->bind([
                'id' => $event->id,
                    'mon' => $this->formData->mon,
                    'tue' => $this->formData->tue,
                    'wed' => $this->formData->wed,
                    'thu' => $this->formData->thu,
                    'fri' => $this->formData->fri,
                    'sat' => $this->formData->sat,
                    'sun' => $this->formData->sun
                ]);
        }
        
        $conflict = $lookupper->checkIsConflict($event, $option);
        
        if(!$conflict) {
            $eventId = $event->save();

            if ($this->formData->isRepeatable) {
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
