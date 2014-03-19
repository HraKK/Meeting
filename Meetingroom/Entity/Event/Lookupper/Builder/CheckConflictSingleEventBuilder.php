<?php
namespace Meetingroom\Entity\Event\Lookupper\Builder;

/**
 * Class CheckConflictSingleEventBuilder
 * @author Denis Maximovskikh <denkin.syneforge.com>
 * @package Meetingroom\Entity\Event\Lookupper\Builder
 */
class CheckConflictSingleEventBuilder
{

    /**
     * @param \Meetingroom\Entity\Event\EventEntity $event
     * @return string
     */
    public function build(\Meetingroom\Entity\Event\EventEntity $event)
    {

        $eventStartTime = date("H:i:s", strtotime($event->dateStart));
        $eventEndTime = date("H:i:s", strtotime($event->dateEnd));
        $weekday = strtolower(date("D", strtotime($event->dateStart)));

        $sql =
            "
            SELECT e.id
            FROM events e
            LEFT JOIN repeating_options r ON ( r.id = e.id)
            WHERE
            e.room_id=1 AND
            (
                (	--Проверка для одноразовых событий
                    (e.date_end BETWEEN '" . $event->dateStart . "' AND '" . $event->dateEnd . "')   OR -- есть ли события  у которых дата конца между новым событием
                    (e.date_start BETWEEN '" . $event->dateStart . "' AND '" . $event->dateEnd . "') OR -- есть ли события у кторых дата начала между новым событием
                    (e.date_start < '" . $event->dateStart . "' AND e.date_end >'" . $event->dateEnd . "') --есть ли события у которых дата начала раньше а конец позже нашего нового события

                ) OR
                (	-- условие для повторяющихся событий
                    e.repeatable=TRUE AND
                    r." . $weekday . " = TRUE AND--день в который хочет стать событие

                    (	--проверяем тут тоже самое что с да только  с временем
                        (e.date_end::time BETWEEN '" . $eventStartTime . "' AND '" . $eventEndTime . "') OR
                        (e.date_start::time BETWEEN '" . $eventStartTime . "' AND '" . $eventEndTime . "') OR
                        (e.date_start::time < '" . $eventStartTime . "' AND e.date_end::time >'" . $eventEndTime . "')

                    )
                )
            )
        ";
        return $sql;
    }

}