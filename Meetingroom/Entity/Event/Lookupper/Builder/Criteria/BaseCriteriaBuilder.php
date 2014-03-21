<?php
namespace Meetingroom\Entity\Event\Lookupper\Builder\Criteria;

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
        $fields_str = (empty($fields)) ? '*' : 'events.' . implode(',events.', $fields);

        return 'SELECT ' . $fields_str . ' FROM events LEFT JOIN repeating_options ON  repeating_options.id=events.id WHERE ';
    }

}