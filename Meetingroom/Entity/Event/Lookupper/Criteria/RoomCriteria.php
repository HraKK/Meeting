<?php
namespace Meetingroom\Entity\Event\Lookupper\Criteria;


class RoomCriteria implements RoomCriteriaInterface
{

    /**
     * @var string sql where
     */
    protected $condition = '';

    /**
     * @param integer $id room id
     */
    public function __construct($id)
    {
        $this->condition = 'rooms.id = ' . intval($id);
    }

    /**
     * @return string where condition
     */
    public function getRoomName()
    {
        return $this->condition;
    }

    /**
     * magic. invoke $this->getRoomName()
     *
     * @return string
     */
    public function __toString()
    {
        $this->getRoomName();
    }

} 