<?php
namespace Meetingroom\Entity\Event\Lookupper;

class EventLookupper implements \Meetingroom\Entity\Event\Lookupper\EventLookupperInterface
{


    /**
     * @var array holder where conditions
     */
    protected $conditions = [];
    protected $di, $db;

    public function __construct($di)
    {
        $this->di = $di;
        $this->db = $this->di->getShared('db');
    }

    /**
     * @param Meetingroom\Entity\Event\Lookupper\RoomCriteriaInterface
     *
     * @return void
     */
    public function setRoomCriteria(Meetingroom\Entity\Event\Lookupper\RoomCriteriaInterface $criteria)
    {
        $this->conditions[] = $criteria;
    }

    /**
     * @param Meetingroom\Entity\Event\Lookupper\PeriodCriteriaInterface
     *
     * @return void
     */
    public function setPeriodCriteria(Meetingroom\Entity\Event\Lookupper\PeriodCriteriaInterface $criteria)
    {
        $this->conditions[] = $criteria;
    }

    /**
     *Return search result
     *
     * @return array of Meetingroom\Entity\Event\Event
     */
    public function lookup()
    {

        $eventLookupperModel = new \Meetingroom\Entity\Event\Lookupper\EventLookupperModel($this->di);
        $eventLookupperModel->setCriterias($this->conditions);

        return $eventLookupperModel->getEvents();
    }


}