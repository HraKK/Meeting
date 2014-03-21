<?php
namespace Meetingroom\Entity\Event\Lookupper;


interface EventLookupperInterface
{

    /**
     * @param Meetingroom\Entity\Event\Lookupper\Criteria\RoomCriteriaInterface
     *
     * @return void
     */
    public function setRoomCriteria(\Meetingroom\Entity\Event\Lookupper\Criteria\RoomCriteriaInterface $criteria);

    /**
     * @param Meetingroom\Entity\Event\Lookupper\Criteria\PeriodCriteriaInterface
     *
     * @return void
     */
    public function setPeriodCriteria(\Meetingroom\Entity\Event\Lookupper\Criteria\PeriodCriteriaInterface $criteria);

    /**
     *Return search result
     *
     * @return array of Meetingroom\Entity\Event\Event
     */
    public function lookup();

} 