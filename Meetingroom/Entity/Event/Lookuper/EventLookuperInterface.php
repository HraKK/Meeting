<?php
namespace Meetingroom\Entity\Event\Lookuper;


interface EventLookuperInterface
{

    /**
     * @param Meetingroom\Entity\Event\Lookuper\RoomCriteriaInterface
     *
     * @return void
     */
    public function setRoomCriteria(Meetingroom\Entity\Event\Lookuper\RoomCriteriaInterface $criteria);

    /**
     * @param Meetingroom\Entity\Event\Lookuper\PeriodCriteriaInterface
     *
     * @return void
     */
    public function setPeriodCriteria(Meetingroom\Entity\Event\Lookuper\PeriodCriteriaInterface $criteria);

    /**
     *Return search result
     *
     * @return array of Meetingroom\Entity\Event\Event
     */
    public function lookup();

} 