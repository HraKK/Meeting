<?php
namespace Meetingroom\Entity\Event\Lookupper\Criteria;

/**
 * Class AbstractPeriodCriteria
 * @package Meetingroom\Entity\Event\Lookupper\Criteria
 * @author Denis Maximovskikh <denkin.syneforge.com>
 */
abstract class AbstractPeriodCriteria implements PeriodCriteriaInterface
{
    /**
     * @var \Meetingroom\Wrapper\DateTime
     */
    protected $startDate;
    /**
     * @var \Meetingroom\Wrapper\DateTime
     */
    protected $endDate;

    /**
     * @return \Meetingroom\Wrapper\DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @return \Meetingroom\Wrapper\DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param \Meetingroom\Wrapper\DateTime $endDate
     */
    public function setEndDate(\Meetingroom\Wrapper\DateTime $endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     * @param \Meetingroom\Wrapper\DateTime $startDate
     */
    public function setStartDate(\Meetingroom\Wrapper\DateTime $startDate)
    {
        $this->startDate = $startDate;
    }


}