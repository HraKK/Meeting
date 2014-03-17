<?php
namespace Meetingroom\Entity\Event\Lookupper\Criteria;

/**
 * Class WeekPeriodCriteria
 * @package Meetingroom\Entity\Event\Lookupper\Criteria
 * @author Denis Maximovskikh <denkin.syneforge.com>
 */
class WeekPeriodCriteria extends AbstractPeriodCriteria
{

    /**
     * @param integer $day
     * @param integer $month
     * @param integer $year
     */
    public function __construct($day, $month, $year)
    {
        $this->startDate = mktime(0, 0, 0, $month, $day, $year);
        $this->endDate = $this->startDate + 86400 * 7;
    }
} 