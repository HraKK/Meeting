<?php
namespace Meetingroom\Entity\Event\Lookupper\Criteria;

/**
 * Class RoomCriteria
 * @package Meetingroom\Entity\Event\Lookupper\Criteria
 * @author Denis Maximovskikh <denkin.syneforge.com>
 */
class RoomCriteria implements RoomCriteriaInterface
{

    protected $id;

    /**
     * @param integer $id room id
     */
    public function __construct($id)
    {
        $this->id = intval($id);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


}