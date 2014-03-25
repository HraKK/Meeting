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
use Phalcon\Validation\Validator\StringLength as StringLength;
use \Meetingroom\View\Engine\JSONEngine;
use \Meetingroom\View\Render;

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

        $this->validator->add(
            'title',
            new StringLength(array(
//                'max' => 5,
                'min' => 3,
//                'messageMaximum' => 'We don\'t like really long names',
                'messageMinimum' => 'Title should be longer'
            ))
        );

        $this->validator->add(
            'description',
            new StringLength(array(
//                'max' => 5,
                'min' => 3,
//                'messageMaximum' => 'We don\'t like really long names',
                'messageMinimum' => 'Description should be longer'
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
        
        $this->validator->setFilters("weekly", "boolean");

        $this->formData = $this->getFormData(true);
    }

    /**
     * @example /event/test_validation?room_id=12aa23&aa=bb
     * @todo delete before stage
     */
    public function test_validationAction()
    {

        var_dump($this->formData);
        var_dump($this->getData('room_id2'));
        var_dump($this->getFormErrors());


        die();
    }

    public function indexAction()
    {
        if (!$this->isAllowed('index', 'index')) {
            $this->onDenied();
        }


        $roomManager = new RoomManager();
        $rooms = $roomManager->getAll();

        $roomCriteria = new RoomCriteria($this->getData('room_id'));
        $periodCriteria = new DayPeriodCriteria($this->getData('day'), $this->getData('month'), $this->getData('year'));
        $roomCriteria = new RoomCriteria($this->getData('room_id'));

        if ($this->getData('weekly') == true) {
            $periodCriteria = new WeekPeriodCriteria($this->getData('day'), $this->getData('month'), $this->getData(
                'year'
            ));
        } else {
            $periodCriteria = new DayPeriodCriteria($this->getData('day'), $this->getData('month'), $this->getData(
                'year'
            ));
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

        $this->view->success = true; 
        $this->view->events = $eventsDTO;
        
        $engine = new JSONEngine();
        $render = new Render();
        
        return $render->process($this->view, $engine);
    }
    
    public function createAction()
    {
        if(!$this->isAllowed('event', 'create')) {
            $this->onDenied();
        }

        if (!empty($this->getFormErrors())) {
            die(json_encode(
                [
                    'success' => false,
                    'errors' => $this->getFormErrors()
                ]
            ));
        }


        $roomManager = new RoomManager();
        if (!$roomManager->isRoomExist($this->getData('room_id'))) {
            $this->sendError('room ain`t exist');
        }

        $lookupper = new EventLookupper($this->di);
        $event = new EventEntity();

        $start = strtotime($this->getData('dateStart'));
        $end = strtotime($this->getData('dateEnd'));

        if ($start === false || $end === false || $end <= $start) {
            $this->sendError('wrong date');
        }
        
        $event->bind([
                'title' => $this->getData('title'),
                'room_id' => $this->getData('room_id'),
                'user_id' => $this->user->id,
                'date_start' => $start,
                'date_end' => $end,
                'description' => $this->getData('description'),
                'repeatable' => $this->getData('isRepeatable'),
                'attendees' => $this->getData('attendees')
            ]);
        
        $option = new EventOptionEntity();

        if ($this->getData('isRepeatable')) {
            $option->bind([
                'id' => $event->id,
                    'mon' => $this->getData('mon'),
                    'tue' => $this->getData('tue'),
                    'wed' => $this->getData('wed'),
                    'thu' => $this->getData('thu'),
                    'fri' => $this->getData('fri'),
                    'sat' => $this->getData('sat'),
                    'sun' => $this->getData('sun'),
                ]);
        }
        
        $conflict = $lookupper->checkIsConflict($event, $option);
        
        if(!$conflict) {
            $eventId = $event->save();
            if(!$eventId){
                $this->sendError('event not created');
            }

            if ($this->getData('isRepeatable')) {
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

        if (!empty($this->getFormErrors())) {
            die(json_encode(
                [
                    'success' => false,
                    'errors' => $this->getFormErrors()
                ]
            ));
        }

        if ($this->getData('room_id') !== $event->roomId) {
            $roomManager = new RoomManager();
            if (!$roomManager->isRoomExist($this->getData('room_id'))) {
                $this->sendError('room ain`t exist');
            }
        }
         
        $lookupper = new EventLookupper($this->di);

        $start = strtotime($this->getData('dateStart'));
        $end = strtotime($this->getData('dateEnd'));

        if ($start === false || $end === false || $end <= $start) {
            $this->sendError('Wrong date');
        }
        
        $event->bind([
                'title' => $this->getData('title'),
                'room_id' => $this->getData('room_id'),
                'date_start' => $start,
                'date_end' => $end,
                'description' => $this->getData('description'),
                'repeatable' => $this->getData('isRepeatable'),
                'attendees' => $this->getData('attendees')
            ]);
        
        $option = new EventOptionEntity();

        if ($this->getData('isRepeatable')) {
            $option->bind([
                'id' => $event->id,
                    'mon' => $this->getData('mon'),
                    'tue' => $this->getData('tue'),
                    'wed' => $this->getData('wed'),
                    'thu' => $this->getData('thu'),
                    'fri' => $this->getData('fri'),
                    'sat' => $this->getData('sat'),
                    'sun' => $this->getData('sun')
                ]);
        }
        
        $conflict = $lookupper->checkIsConflict($event, $option);
        
        if(!$conflict) {
            $eventId = $event->save();

            if ($this->getData('isRepeatable')) {
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
