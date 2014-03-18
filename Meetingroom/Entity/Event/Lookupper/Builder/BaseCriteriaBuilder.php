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
     * @param array $fields
     * @return string
     */
    public function build(array $fields = [])
    {
        $fields_str = (empty($fields)) ? '*' : implode(',', $fields);

        return 'SELECT ' . $fields_str . ' FROM events WHERE ';
    }

}