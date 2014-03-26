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
     * @param \DateTime $dateTime
     */
    public function __construct(\Meetingroom\Wrapper\DateTime $dateTime)
    {
        $this->setStartDate($dateTime);
        $dayInMonth = $dateTime->format('t');
        $nextMonth = $dateTime->add(new \DateInterval('P' . $dayInMonth . 'D'));
        $this->setEndDate($nextMonth);
    }

} 