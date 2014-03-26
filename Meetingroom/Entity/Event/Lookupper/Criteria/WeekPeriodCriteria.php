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
     * @param \Meetingroom\Wrapper\DateTime $dateTime
     */
    public function __construct(\Meetingroom\Wrapper\DateTime $dateTime)
    {
        $this->setStartDate($dateTime);

        $nextWeek = clone $dateTime;
        $nextWeek->add(new \DateInterval('P7D'));

        $this->setEndDate($nextWeek);
    }

} 