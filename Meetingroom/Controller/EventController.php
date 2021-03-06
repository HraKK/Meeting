<?php

namespace Meetingroom\Controller;

use \Meetingroom\Entity\Room\RoomManager;
use \Meetingroom\Entity\Event\EventOptionEntity;
use \Meetingroom\Entity\Event\EventEntity;
use \Meetingroom\Entity\Event\Lookupper\EventLookupper;
use \Meetingroom\Entity\Event\Lookupper\Criteria\DayPeriodCriteria;
use \Meetingroom\Entity\Event\Lookupper\Criteria\RoomCriteria;
use \Meetingroom\Entity\Event\Lookupper\Criteria\WeekPeriodCriteria;
use \Phalcon\Validation\Validator\Regex as RegexValidator;
use \Phalcon\Validation\Validator\StringLength as StringLength;
use \Phalcon\Mvc\Model\Message as Message;

class EventController extends AbstractController
{
    public function initialize()
    {
        parent::initialize();

        $this->validator = new \Phalcon\Validation();

        $this->validator->add(
                'room_id', new RegexValidator(array(
            'pattern' => '/^\d*$/',
            'message' => 'Room id must be integer'
                ))
        );

        $this->validator->add(
                'title', new StringLength(array(
//                'max' => 5,
            'min' => 3,
//                'messageMaximum' => 'We don\'t like really long names',
            'messageMinimum' => 'Title should be longer'
                ))
        );

        $this->validator->add(
                'description', new StringLength(array(
//                'max' => 5,
            'min' => 3,
//                'messageMaximum' => 'We don\'t like really long names',
            'messageMinimum' => 'Description should be longer'
                ))
        );

        $filter = $this->di->getShared("filter");
        $filter->add(
                'boolean', function ($value) {
            return (bool) $value;
        }
        );

        $this->validator->setFilters("day", "int");
        $this->validator->setFilters("month", "int");
        $this->validator->setFilters("year", "int");

        $this->validator->setFilters('room_id', 'int');
        $this->validator->setFilters("title", "striptags");
        $this->validator->setFilters("repeatable", "int");
        $this->validator->setFilters("date_start", "int");
        $this->validator->setFilters("date_end", "int");
        $this->validator->setFilters("description", "striptags");
        $this->validator->setFilters("attendees", "int");

        $this->validator->setFilters("mon", "int");
        $this->validator->setFilters("tue", "int");
        $this->validator->setFilters("wed", "int");
        $this->validator->setFilters("thu", "int");
        $this->validator->setFilters("fri", "int");
        $this->validator->setFilters("sat", "int");
        $this->validator->setFilters("sun", "int");

        $this->validator->setFilters("weekly", 'boolean');

        $this->formData = $this->getFormData(true);

    }

    /**
     * @example /event/test_validation?room_id=12aa23&aa=bb
     * @todo delete before stage
     */
//    public function test_validationAction()
//    {
//
//        $startDate = new \Meetingroom\Wrapper\DateTime();
//        $startDate->setDate(2013, 10, 15);
//        var_dump($startDate);
//        $nextWeek = $startDate->add(new \DateInterval('P7D'));
//        var_dump($startDate);
//        die();
//
//        $startDate->setTimestamp(time() + 500);
//
//        $endDate = new \Meetingroom\Wrapper\DateTime();
//        $endDate->setTimestamp(time());
//
//        print($startDate);
//        die();
//        $msg = new Message('message');
//
//        //var_dump($msg->getMessage());
//
//        $this->sendError(new Message('message'));
//
//
//        var_dump($this->formData);
////        var_dump($this->getData('room_id2'));
//        var_dump($this->getFormErrors());
//
//        die();
//    }

    public function indexAction()
    {
        if (!$this->isAllowed('index', 'index')) {
            return $this->onDenied();
        }

        $roomCriteria = new RoomCriteria($this->getData('room_id'));
        $date = new \Meetingroom\Wrapper\DateTime();

        $date->setDate($this->getData('year'), $this->getData('month'), $this->getData('day'));
        $date->setTime(0, 0, 0);

        if ($this->getData('weekly') == true) {
            $periodCriteria = new WeekPeriodCriteria($date);
        } else {
            $periodCriteria = new DayPeriodCriteria($date);
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

        return $this->render();
    }

    public function createAction()
    {
        if (!$this->isAllowed('event', 'create')) {
            return $this->onDenied();
        }

        $this->view->success = false;

        $errors = $this->getFormErrors();
        if (!empty($errors)) {
            $this->view->errors = $errors;
            return $this->render();
        }

        $lookupper = new EventLookupper($this->di);
        $event = new EventEntity();

        $roomManager = new RoomManager();
        if (!$roomManager->isRoomExist($this->getData('room_id'))) {
            return $this->sendError(new Message('room ain\'t exist'));
        }

        $startDate = new \Meetingroom\Wrapper\DateTime();
        $endDate = new \Meetingroom\Wrapper\DateTime();
        $nowDate = new \Meetingroom\Wrapper\DateTime(date("Y-m-d"));

        $endDate->setTimestamp($this->getData('date_end'));
        $startDate->setTimestamp($this->getData('date_start'));


        if ($startDate === false || $endDate === false || $endDate <= $startDate ||
                ($nowDate > $startDate && $this->getData('repeatable') == false)) {
            return $this->sendError(new Message('wrong date'));
        }
        
        $event->bind([
            'title' => $this->getData('title'),
            'room_id' => $this->getData('room_id'),
            'user_id' => $this->user->id,
            'date_start' => $startDate,
            'date_end' => $endDate,
            'description' => $this->getData('description'),
            'repeatable' => (int) $this->getData('repeatable'),
            'attendees' => $this->getData('attendees')
        ]);

        $option = new EventOptionEntity();
        
        if ($this->getData('repeatable')) {
            $repeatedOn = $this->getData('repeated_on');
            
            if(empty($repeatedOn) == true || is_array($repeatedOn) == false) {
                return $this->sendError(new Message('Repeated events should have more than one day of week'));
            }
            
            $map = [ 'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun' ];
            $repeated = array_flip(array_map(function($repeat) use ($map) {
                return $map[$repeat];
            }, $repeatedOn));
        
            $option->bind([
                'id' => $event->id,
                'mon' => isset($repeated['mon']) ? 1 : 0,
                'tue' => isset($repeated['tue']) ? 1 : 0,
                'wed' => isset($repeated['wed']) ? 1 : 0,
                'thu' => isset($repeated['thu']) ? 1 : 0,
                'fri' => isset($repeated['fri']) ? 1 : 0,
                'sat' => isset($repeated['sat']) ? 1 : 0,
                'sun' => isset($repeated['sun']) ? 1 : 0,
            ]);
        }

        $conflict = $lookupper->checkIsConflict($event, $option);

        if (!$conflict) {
            $eventId = $event->save();
            if (!$eventId) {
                return $this->sendError(new Message('event not created'));
            }

            if ($this->getData('repeatable')) {
                $option->bind(['id' => $event->id])->insert();
            }

            $this->view->success = true;
            $this->view->id = $eventId;
            $this->view->errors = $errors;
            return $this->render();
        } else {
            $conflicts = [];
            foreach ($conflict as $event) {
                $conflicts[] = $event->getDTO();
            }
            
            $this->view->conflicts = $conflicts;
            return $this->sendError(new Message('event conflict with other events'));
        }
    }

    public function updateAction()
    {
        $event = $this->getEventByRequest();
        $this->view->success = false;

        $role = $this->getRoleFactory()->getRoleInEvent($this->user, $event);
        if (!$this->isAllowed('event', 'update', $role)) {
            return $this->onDenied();
        }

        $errors = $this->getFormErrors();
        if (!empty($errors)) {
            $this->view->errors = $errors;
            return $this->render();
        }

        if ($this->getData('room_id') !== $event->roomId) {
            $roomManager = new RoomManager();
            if (!$roomManager->isRoomExist($this->getData('room_id'))) {
                return $this->sendError(new Message('room ain`t exist'));
            }
        }

        $lookupper = new EventLookupper($this->di);

        $startDate = new \Meetingroom\Wrapper\DateTime();
        $endDate = new \Meetingroom\Wrapper\DateTime();
        $nowDate = new \Meetingroom\Wrapper\DateTime(date("Y-m-d"));
        
        $startDate->setTimestamp($this->getData('date_start'));
        $endDate->setTimestamp($this->getData('date_end'));

        if ($startDate === false || $endDate === false || $endDate <= $startDate ||
                ($nowDate > $startDate && $this->getData('repeatable') == false)) {
            return $this->sendError(new Message('wrong date'));
        }

        $needInsertOption = ($event->repeatable != (int) $this->getData('repeatable')) ? true : false;

        $event->bind([
            'title' => $this->getData('title'),
            'room_id' => $this->getData('room_id'),
            'date_start' => $startDate,
            'date_end' => $endDate,
            'description' => $this->getData('description'),
            'repeatable' => (int) $this->getData('repeatable'),
            'attendees' => $this->getData('attendees')
        ]);

        $option = new EventOptionEntity();

        if ($this->getData('repeatable')) {
            $repeatedOn = $this->getData('repeated_on');
            
            if(empty($repeatedOn) == true || is_array($repeatedOn) == false) {
                return $this->sendError(new Message('Repeated events should have more than one day of week'));
            }
            
            $map = [ 'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun' ];
            $repeated = array_flip(array_map(function($repeat) use ($map) {
                return $map[$repeat];
            }, $repeatedOn));
        
            $option->bind([
                'id' => $event->id,
                'mon' => isset($repeated['mon']) ? 1 : 0,
                'tue' => isset($repeated['tue']) ? 1 : 0,
                'wed' => isset($repeated['wed']) ? 1 : 0,
                'thu' => isset($repeated['thu']) ? 1 : 0,
                'fri' => isset($repeated['fri']) ? 1 : 0,
                'sat' => isset($repeated['sat']) ? 1 : 0,
                'sun' => isset($repeated['sun']) ? 1 : 0,
            ]);
        }

        $conflict = $lookupper->checkIsConflict($event, $option);

        if (!$conflict) {
            $eventId = $event->save();

            if ($this->getData('repeatable')) {
                $needInsertOption ? $option->insert() : $option->update();
            } else {
                $option->delete();
            }

            $this->view->success = true;
            return $this->render();
        } else {
            $conflicts = [];
            foreach ($conflict as $event) {
                $conflicts[] = $event->getDTO();
            }
            
            $this->view->conflicts = $conflicts;
            return $this->sendError(new Message('event conflict with other events'));
        }
    }

    public function deleteAction()
    {
        $event = $this->getEventByRequest();
        $this->view->success = false;
        $role = $this->getRoleFactory()->getRoleInEvent($this->user, $event);
        if (!$this->isAllowed('event', 'delete', $role)) {
            return $this->onDenied();
        }

        if ($event->delete() == true) {
            $this->view->success = true;
            return $this->render();
        } else {
            return $this->sendError(new Message('false'));
        }
    }

    protected function getEventByRequest()
    {
        $eventId = $this->getData("id");

        $event = new EventEntity($eventId);
        if ($event->isLoaded() === false) {

            $this->sendError(new Message('event not found'));
        }

        return $event;
    }

}
