<?php
namespace Meetingroom\Entity\Event\Lookupper\Builder;

/**
 * Class PeriodCriteriaBuilder
 * Return main chunk of select statement
 * @author Denis Maximovskikh <denkin.syneforge.com>
 */
class BaseCriteriaBuilder
{

    /**
     * @return string
     */
    public function build()
    {
        return 'SELECT * FROM events WHERE ';
    }

}