<?php
namespace Meetingroom\Entity\Event\Lookupper\Builder\CheckConflict;

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

        //-- условие для повторяющихся событий
        //--проверяем тут тоже самое что с датой только  с временем
        $sql =
            "OR
                (
                    e.repeatable=TRUE AND
                    r." . $weekday . " = TRUE AND--день в который хочет стать событие

                    (
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