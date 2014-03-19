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
     * @param \Meetingroom\Entity\Event\EventEntity $event
     * @return string
     */
    public function build(
        \Meetingroom\Entity\Event\EventEntity $event,
        \Meetingroom\Entity\Event\EventOptionEntity $options
    ) {
        if ($event->repeatable) {
            $conflictBuilder = new CheckConflictRepeatableEventBuilder();
            $sql = $conflictBuilder->build($event, $options);
        } else {
            $conflictBuilder = new CheckConflictSingleEventBuilder();
            $sql = $conflictBuilder->build($event);
        }

        return $sql;
    }

}