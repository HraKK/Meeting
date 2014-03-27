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

        $weekday = strtolower($event->dateStart->format('D'));

        //-- условие для повторяющихся событий
        //--проверяем тут тоже самое что с датой только  с временем
        $sql =
            "OR
                (
                    e.repeatable=TRUE AND
                    r." . $weekday . " = TRUE AND--день в который хочет стать событие

                    (
                        (e.date_end::time > '" . $event->dateStart->format('H:i:s') . "' AND e.date_end::time <'" . $event->dateEnd->format('H:i:s') . "') OR
                        (e.date_start::time > '" . $event->dateStart->format('H:i:s') . "' AND e.date_start::time  < '" . $event->dateEnd->format('H:i:s') . "') OR
                        (e.date_start::time < '" . $event->dateStart->format('H:i:s') . "' AND e.date_end::time >'" . $event->dateEnd->format('H:i:s') . "')

                    )
                )
            )
        ";
        return $sql;
    }

}