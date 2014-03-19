<?php
namespace Meetingroom\Entity\Event\Lookupper\Builder;

/**
 * Class CheckConflictRepeatableEventBuilder
 * @author Denis Maximovskikh <denkin.syneforge.com>
 * @package Meetingroom\Entity\Event\Lookupper\Builder
 */
class CheckConflictRepeatableEventBuilder
{

    /**
     * @param \Meetingroom\Entity\Event\EventEntity $event
     * @param \Meetingroom\Entity\Event\EventOptionEntity $options
     * @return string
     */
    public function build(
        \Meetingroom\Entity\Event\EventEntity $event,
        \Meetingroom\Entity\Event\EventOptionEntity $options
    ) {


        $options_fields = [1 => 'mon', 2 => 'tue', 3 => 'wed', 4 => 'thu', 5 => 'fri', 6 => 'sat', 0 => 'sun'];

        $sql_weekday_part = [];
        $weekday_arr_int = [];
        foreach ($options_fields as $key => $weekday) {
            if ($options->$weekday) {
                $sql_weekday_part[] = " r." . $weekday . " = TRUE ";
                $weekday_arr_int[] = $key;
            }
        }

        $eventStartTime = date("H:i:s", strtotime($event->dateStart));
        $eventEndTime = date("H:i:s", strtotime($event->dateEnd));


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
                    (" . implode(" OR ", $sql_weekday_part) . ") AND--день в который хочет стать событие

                    (	--проверяем тут тоже самое что с да только  с временем
                        (e.date_end::time BETWEEN '" . $eventStartTime . "' AND '" . $eventEndTime . "') OR
                        (e.date_start::time BETWEEN '" . $eventStartTime . "' AND '" . $eventEndTime . "') OR
                        (e.date_start::time < '" . $eventStartTime . "' AND e.date_end::time >'" . $eventEndTime . "')
                    )
                ) OR
                ( --проверка одноразовых событий в будущем
                    e.date_start >  '" . $event->dateEnd . "' AND
                    (	--проверяем тут тоже самое что с да только  с временем
                        (e.date_end::time BETWEEN '" . $eventStartTime . "' AND '" . $eventEndTime . "') OR
                        (e.date_start::time BETWEEN '" . $eventStartTime . "' AND '" . $eventEndTime . "') OR
                        (e.date_start::time < '" . $eventStartTime . "' AND e.date_end::time >'" . $eventEndTime . "')
                    ) AND
                    extract(DOW from date_start) IN (" . implode(",", $weekday_arr_int) . ")
                )
            )
        ";

        return $sql;

    }

}