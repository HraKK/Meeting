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
     * @var integer timestamp
     */
    protected $startDate;
    /**
     * @var integer timestamp
     */
    protected $endDate;

    /**
     * @return integer timestamp
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @return integer timestamp
     */
    public function getStartDate()
    {
        return $this->startDate;
    }


}