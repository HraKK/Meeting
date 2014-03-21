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
     * @var string ISO 8601
     */
    protected $startDate;
    /**
     * @var string ISO 8601
     */
    protected $endDate;

    /**
     * @return string ISO 8601
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @return string ISO 8601
     */
    public function getStartDate()
    {
        return $this->startDate;
    }


}