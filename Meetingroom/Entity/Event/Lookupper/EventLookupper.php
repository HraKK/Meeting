<?php
namespace Meetingroom\Entity\Event\Lookupper;

use \Meetingroom\Entity\Event\Lookupper\Criteria\RoomCriteriaInterface;
use \Meetingroom\Entity\Event\Lookupper\Criteria\PeriodCriteriaInterface;
use \Meetingroom\Entity\Event\EventEntity;
use \Meetingroom\Entity\Event\EventOptionEntity;

/**
 * Class EventLookupper
 * @package Meetingroom\Entity\Event\Lookupper
 * @author Denis Maximovskikh <denkin.syneforge.com>
 */
class EventLookupper implements \Meetingroom\Entity\Event\Lookupper\EventLookupperInterface
{
    protected $roomCriteria;
    protected $periodCriteria;
    protected $di;
    protected $db;
    protected $fields = [];


    public function __construct($di)
    {
        $this->di = $di;
        $this->db = $this->di->getShared('db');
    }

    /**
     * @param RoomCriteriaInterface $criteria
     *
     * @return Lookupper
     */
    public function setRoomCriteria(RoomCriteriaInterface $criteria)
    {
        $this->roomCriteria = $criteria;
        return $this;
    }

    /**
     * @param PeriodCriteriaInterface $criteria
     *
     * @return Lookupper
     */
    public function setPeriodCriteria(PeriodCriteriaInterface $criteria)
    {
        $this->periodCriteria = $criteria;
        return $this;
    }

    /**
     * @param array $fields
     * @return Lookupper
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     *
     * @param \Meetingroom\Entity\Event\EventEntity $event
     * @param \Meetingroom\Entity\Event\EventOptionEntity $options
     * @return boolean
     */
    public function checkIsConflict(
        EventEntity $event,
        EventOptionEntity $options = null
    ) {

        $eventLookupperModel = new \Meetingroom\Entity\Event\Lookupper\EventLookupperModel();

        return $eventLookupperModel->checkIsConflict($event, $options);
    }

    /**
     *Return search result
     *
     * @return array of Meetingroom\Entity\Event\Event
     */
    public function lookup()
    {

        $eventLookupperModel = new \Meetingroom\Entity\Event\Lookupper\EventLookupperModel();


        return $eventLookupperModel->getEvents($this->roomCriteria, $this->periodCriteria, $this->fields);
    }


}