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
     * @param \Meetingroom\Wrapper\DateTime $dateTime
     */
    public function __construct(\Meetingroom\Wrapper\DateTime $dateTime)
    {
        $this->setStartDate($dateTime);
        $nextMonth = clone $dateTime;
        $dayInMonth = $dateTime->format('t');
        $nextMonth->add(new \DateInterval('P' . $dayInMonth . 'D'));
        $this->setEndDate($nextMonth);
    }

} 