<?php
namespace Meetingroom\Entity\Event\Lookupper\Builder\CheckConflict;

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

        $eventStartTime = $event->dateStart->format('H:i:s');
        $eventEndTime = $event->dateEnd->format('H:i:s');

        //--условие для повторяющихся событий
        //--день в который хочет стать событие
        //--проверяем тут тоже самое что с да только  с временем
        //--проверка одноразовых событий в будущем
        //--проверяем тут тоже самое что с да только  с временем
        $sql =
            "
            OR
                (
                    e.repeatable=TRUE AND
                    (" . implode(" OR ", $sql_weekday_part) . ") AND

                    (
                        (e.date_end::time   > '" . $eventStartTime . "' AND e.date_end::time   < '" . $eventEndTime . "') OR
                        (e.date_start::time > '" . $eventStartTime . "' AND e.date_start::time < '" . $eventEndTime . "') OR
                        (e.date_start::time < '" . $eventStartTime . "' AND e.date_end::time   > '" . $eventEndTime . "')
                    )
                ) OR
                (
                    e.date_start >  '" . $event->dateEnd->format('Y-m-d H:i:s') . "' AND
                    (
                        (e.date_end::time   > '" . $eventStartTime . "' AND e.date_end::time   < '" . $eventEndTime . "') OR
                        (e.date_start::time > '" . $eventStartTime . "' AND e.date_start::time < '" . $eventEndTime . "') OR
                        (e.date_start::time < '" . $eventStartTime . "' AND e.date_end::time   > '" . $eventEndTime . "')
                    ) AND
                    EXTRACT(DOW from date_start) IN (" . implode(",", $weekday_arr_int) . ")
                )
            )
        ";

        return $sql;

    }

}