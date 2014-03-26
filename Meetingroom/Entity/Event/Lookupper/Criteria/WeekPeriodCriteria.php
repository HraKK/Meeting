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
     * @param \DateTime $dateTime
     */
    public function __construct(\Meetingroom\Wrapper\DateTime $dateTime)
    {
        $this->setStartDate($dateTime);
        $nextWeek = $dateTime->add(new \DateInterval('P7D'));
        $this->setEndDate($nextWeek);
    }

} 