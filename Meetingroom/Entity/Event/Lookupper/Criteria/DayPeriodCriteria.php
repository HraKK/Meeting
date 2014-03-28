<?php
namespace Meetingroom\Entity\Event\Lookupper\Criteria;


/**
 * Class DayPeriodCriteria
 * @author Denis Maximovskikh <denkin.syneforge.com>
 * @package Meetingroom\Entity\Event\Lookupper\Criteria
 */
class DayPeriodCriteria extends AbstractPeriodCriteria
{
    /**
     * @param \Meetingroom\Wrapper\DateTime $dateTime
     */
    public function __construct(\Meetingroom\Wrapper\DateTime $dateTime)
    {
        $this->setStartDate($dateTime);
        $nextDay = clone $dateTime;

        $nextDay->add(new \DateInterval('P1D'));
        $this->setEndDate($nextDay);
    }

} 