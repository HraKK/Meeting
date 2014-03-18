<?php
namespace Meetingroom\Entity\Event\Lookupper\Builder;

use \Meetingroom\Entity\Event\Lookupper\Criteria;

/**
 * Class MainEventBuilder
 * Return all sql select statement
 * @author Denis Maximovskikh <denkin.syneforge.com>
 */
class EventBuilder
{

    /**
     * @param RoomCriteriaInterface $roomCriteria
     * @param PeriodCriteriaInterface $periodCriteria
     * @return string
     */
    public function build(
        \Meetingroom\Entity\Event\Lookupper\Criteria\RoomCriteriaInterface $roomCriteria,
        \Meetingroom\Entity\Event\Lookupper\Criteria\PeriodCriteriaInterface $periodCriteria
    ) {
        $sql = '';
        $baseCriteriaBuilder = new BaseCriteriaBuilder();
        $roomCriteriaBuilder = new RoomCriteriaBuilder();
        $periodCriteriaBuilder = new PeriodCriteriaBuilder();


        $sql .= $baseCriteriaBuilder->build();
        $sql .= $roomCriteriaBuilder->build($roomCriteria);
        $sql .= ' AND ';
        $sql .= $periodCriteriaBuilder->build($periodCriteria);

        return $sql;
    }

}