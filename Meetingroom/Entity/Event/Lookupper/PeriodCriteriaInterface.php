<?php
namespace Meetingroom\Entity\Event\Lookupper;

interface PeriodCriteriaInterface
{

    /**
     * @return string where condition
     */
    public function getPeriod();

    /**
     * magic. invoke $this->getperiod()
     *
     * @return string
     */
    public function __toString();
} 