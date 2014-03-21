<?php
namespace Meetingroom\Entity\Event\Lookupper\Criteria;

/**
 * Interface RoomCriteriaInterface
 * @package Meetingroom\Entity\Event\Lookupper\Criteria
 * @author Denis Maximovskikh <denkin.syneforge.com>
 */
interface RoomCriteriaInterface
{

    /**
     * @param integer $id room id
     */
    public function __construct($id);

}