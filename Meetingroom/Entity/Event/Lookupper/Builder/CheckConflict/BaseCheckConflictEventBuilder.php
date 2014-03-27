<?php
namespace Meetingroom\Entity\Event\Lookupper\Builder\CheckConflict;

/**
 * Class CheckConflictSingleEventBuilder
 * @author Denis Maximovskikh <denkin.syneforge.com>
 * @package Meetingroom\Entity\Event\Lookupper\Builder
 */
class BaseCheckConflictEventBuilder
{

    /**
     * @param \Meetingroom\Entity\Event\EventEntity $event
     * @return string
     */
    public function build(\Meetingroom\Entity\Event\EventEntity $event)
    {

        //--Проверка для одноразовых событий
        //-- есть ли события  у которых дата конца между новым событием
        //-- есть ли события у кторых дата начала между новым событием
        // --есть ли события у которых дата начала раньше а конец позже нашего нового события

        //Exclude self id from search result
        $exclude_id = '';
        if ($event->id) {
            $exclude_id = "e.id<>" . $event->id . " AND ";
        }
        $sql =
            "
            SELECT e.id
            FROM events e
            LEFT JOIN repeating_options r ON ( r.id = e.id)
            WHERE
             e.room_id=" . $event->roomId . " AND
             " . $exclude_id . "
            (
                (
                    (e.date_end   > '" . $event->dateStart . "' AND e.date_end   <= '" . $event->dateEnd . "')   OR
                    (e.date_start >= '" . $event->dateStart . "' AND e.date_start < '" . $event->dateEnd . "') OR
                    (e.date_start < '" . $event->dateStart . "' AND e.date_end   > '" . $event->dateEnd . "')

                )";

        return $sql;
    }

}