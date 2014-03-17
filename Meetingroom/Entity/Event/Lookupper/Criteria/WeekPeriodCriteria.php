<?php
namespace Meetingroom\Entity\Event\Lookupper\Criteria;


class WeekPeriodCriteria implements PeriodCriteriaInterface
{

    /**
     * @var string sql where
     */
    protected $condition;

    /**
     * @param integer $day
     * @param integer $month
     * @param integer $year
     */
    public function __construct($day, $month, $year)
    {
        $unix_day_start = mktime(0, 0, 0, $month, $day, $year);
        $unix_day_end = $unix_day_start + 86400 * 7;
        $condition = 'events.date_start BETWEEN  ' . $unix_day_start . ' AND ' . $unix_day_end;

        return $condition;
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