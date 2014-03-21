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
        $unix_start_date = mktime(0, 0, 0, $month, $day, $year);
        $this->startDate = date('c', $unix_start_date);
        $this->endDate = date('c', $unix_start_date + (86400 * 7));
    }
} 