<?php
namespace Meetingroom\Entity\Event\Lookupper\Criteria;


class MonthPeriodCriteria implements PeriodCriteriaInterface
{

    /**
     * @var string sql where
     */
    protected $condition;

    /**
     * @param integer $month
     * @param integer $year
     */
    public function __construct($month, $year)
    {
        $unix_day_start = mktime(0, 0, 0, $month, 1, $year);
        $day_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $unix_day_end = $unix_day_start + (86400 * $day_in_month);
        $condition = 'events.date_start BETWEEN  ' . $unix_day_start . ' AND ' . $unix_day_end;

        return $condition;
        //TODO: add repeatable logic!
    }


    /**
     * @return string where condition
     */
    public function getPeriod()
    {
        return $this->condition;
    }

    /**
     * magic. invoke $this->getperiod()
     *
     * @return string
     */
    public function __toString()
    {
        $this->getPeriod();
    }

} 