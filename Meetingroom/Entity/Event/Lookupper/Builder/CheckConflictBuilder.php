<?php
namespace Meetingroom\Entity\Event\Lookupper\Builder;

/**
 * Class CheckConflictBuilder
 * @author Denis Maximovskikh <denkin.syneforge.com>
 * @package Meetingroom\Entity\Event\Lookupper\Builder
 */
class CheckConflictBuilder
{

    /**
     * @return string
     */
    public function build($room, $weekday)
    {
        return sprintf(
            "
                      SELECT
                        e.id,
                        extract(hour from e.date_start) as h,
                        extract(minute from e.date_end) as m,
                      FROM events e
                      LEFT JOIN repeating_options r ON ( r.id = e.id)
                      WHERE e.room_id = %d
                        AND ((e.repeatable = TRUE AND r.'%s' = TRUE)
                        OR date_trunc('day', e.date_start) =  date_trunc('day', now()));
                    ",
            $room,
            $weekday
        );
    }

}