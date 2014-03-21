<?php
namespace Meetingroom\Entity\Event\Lookupper\Builder\Criteria;

/**
 * Class RoomCriteriaBuilder
 * Return room chunk of sql statement
 * @author Denis Maximovskikh <denkin.syneforge.com>
 */
class RoomCriteriaBuilder
{

    /**
     * @param \Meetingroom\Entity\Event\Lookupper\Criteria\RoomCriteria $criteria
     * @return string
     */
    public function build(\Meetingroom\Entity\Event\Lookupper\Criteria\RoomCriteria $criteria)
    {
        return ' events.room_id = ' . intval($criteria->getId()) . ' ';
    }

}