<?php
namespace Meetingroom\Entity\Event\Lookupper\Builder;

use \Meetingroom\Entity\Event\Lookupper\Criteria\RoomCriteriaInterface;
use \Meetingroom\Entity\Event\Lookupper\Criteria\PeriodCriteriaInterface;

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
     * @param array $fields
     * @return string
     */
    public function build(
        RoomCriteriaInterface $roomCriteria,
        PeriodCriteriaInterface $periodCriteria,
        array $fields = []
    ) {
        $sql = '';
        $baseCriteriaBuilder = new BaseCriteriaBuilder();
        $roomCriteriaBuilder = new RoomCriteriaBuilder();
        $periodCriteriaBuilder = new PeriodCriteriaBuilder();


        $sql .= $baseCriteriaBuilder->build($fields);
        $sql .= $roomCriteriaBuilder->build($roomCriteria);
        $sql .= ' AND ';
        $sql .= $periodCriteriaBuilder->build($periodCriteria);

        return $sql;
    }

}