<?php
namespace Meetingroom\Entity\Event\Lookupper\Criteria;

/**
 * Class MonthPeriodCriteria
 * @package Meetingroom\Entity\Event\Lookupper\Criteria
 * @author Denis Maximovskikh <denkin.syneforge.com>
 */
class MonthPeriodCriteria extends AbstractPeriodCriteria
{

    /**
     * @param integer $month
     * @param integer $year
     */
    public function __construct($month, $year)
    {
        $unix_start_date = mktime(0, 0, 0, $month, 1, $year);
        $this->startDate = date('c', $unix_start_date);
        $day_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $this->endDate = date('c', $unix_start_date + (86400 * $day_in_month));
    }

} 