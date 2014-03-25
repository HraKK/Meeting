<?php
namespace Meetingroom\Entity\Event\Lookupper\Builder\Criteria;

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
     * @param null|RoomCriteriaInterface $roomCriteria
     * @param PeriodCriteriaInterface $periodCriteria
     * @param array $fields
     * @return string
     */
    public function build(
        RoomCriteriaInterface $roomCriteria = null,
        PeriodCriteriaInterface $periodCriteria,
        array $fields = []
    ) {
        $sql = '';
        $baseCriteriaBuilder = new BaseCriteriaBuilder();
        $sql .= $baseCriteriaBuilder->build($fields);

        if ($roomCriteria !== null || $roomCriteria->getId() == 0) {
            $roomCriteriaBuilder = new RoomCriteriaBuilder();
            $sql .= $roomCriteriaBuilder->build($roomCriteria);
            $sql .= ' AND ';
        }


        $periodCriteriaBuilder = new PeriodCriteriaBuilder();
        $sql .= ' AND ';
        $sql .= $periodCriteriaBuilder->build($periodCriteria);

        return $sql;
    }

}