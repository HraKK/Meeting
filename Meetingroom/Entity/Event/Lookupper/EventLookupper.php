<?php
namespace Meetingroom\Entity\Event\Lookupper;

class EventLookupper implements \Meetingroom\Entity\Event\Lookupper\EventLookupperInterface
{
    protected $roomCriteria;
    protected $periodCriteria;
    protected $di;
    protected $db;

    public function __construct($di)
    {
        $this->di = $di;
        $this->db = $this->di->getShared('db');
    }

    /**
     * @param Meetingroom\Entity\Event\Lookupper\Criteria\RoomCriteriaInterface
     *
     * @return void
     */
    public function setRoomCriteria(\Meetingroom\Entity\Event\Lookupper\Criteria\RoomCriteriaInterface $criteria)
    {
        $this->roomCriteria = $criteria;
    }

    /**
     * @param \Meetingroom\Entity\Event\Lookupper\Criteria\PeriodCriteriaInterface
     *
     * @return void
     */
    public function setPeriodCriteria(\Meetingroom\Entity\Event\Lookupper\Criteria\PeriodCriteriaInterface $criteria)
    {
        $this->periodCriteria = $criteria;
    }

    /**
     *Return search result
     *
     * @return array of Meetingroom\Entity\Event\Event
     */
    public function lookup()
    {

        $eventLookupperModel = new \Meetingroom\Entity\Event\Lookupper\EventLookupperModel($this->di);


        return $eventLookupperModel->getEvents($this->roomCriteria, $this->periodCriteria);
    }


}