<?php
namespace Meetingroom\Entity\Event\Lookupper\Builder\Criteria;

use \Meetingroom\Entity\Event\Lookupper\Criteria\DayPeriodCriteria;

/**
 * Class PeriodCriteriaBuilder
 * Return period chunk of sql statement
 * @author Denis Maximovskikh <denkin.syneforge.com>
 * @package Meetingroom\Entity\Event\Lookupper\Builder
 */
class PeriodCriteriaBuilder
{

    /**
     * @param \Meetingroom\Entity\Event\Lookupper\Criteria\PeriodCriteriaInterface $criteria
     * @return string
     */
    public function build(\Meetingroom\Entity\Event\Lookupper\Criteria\PeriodCriteriaInterface $criteria)
    {
        $dayCondition = '';
        if ($criteria instanceof DayPeriodCriteria) {
            $unixStartDate = strtotime($criteria->getStartDate());
            $weekDay = strtolower(date('D', $unixStartDate));
            $dayCondition = sprintf(' AND repeating_options.%s=TRUE ', $weekDay);
        }

        return " ((events.date_start BETWEEN  '" . $criteria->getStartDate() . "' AND '" . $criteria->getEndDate(
        ) . "') OR (events.repeatable=TRUE " . $dayCondition . ")) ";
    }

}