<?php
namespace Meetingroom\Entity\Event\Lookupper\Builder;

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
        return " events.date_start BETWEEN  '" . $criteria->getStartDate() . "' AND '" . $criteria->getEndDate() . "' ";
    }

}