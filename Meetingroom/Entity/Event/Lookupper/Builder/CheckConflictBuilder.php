<?php
namespace Meetingroom\Entity\Event\Lookupper\Builder;

use \Meetingroom\Entity\Event\EventEntity;
use \Meetingroom\Entity\Event\EventOptionEntity;

/**
 * Class CheckConflictBuilder
 * @author Denis Maximovskikh <denkin.syneforge.com>
 * @package Meetingroom\Entity\Event\Lookupper\Builder
 */
class CheckConflictBuilder
{

    /**
     * @param \Meetingroom\Entity\Event\EventEntity $event
     * @param \Meetingroom\Entity\Event\EventOptionEntity $options
     * @return string
     */
    public function build(EventEntity $event, EventOptionEntity $options = null)
    {
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