<?php
namespace Meetingroom\Entity\Event\Lookupper;

interface RoomCriteriaInterface
{

    /**
     * @param integer $id room id
     */
    public function __construct($id);

    /**
     * @return string where condition
     */
    public function getRoomName();

    /**
     * magic. invoke $this->getRoomName()
     *
     * @return string
     */
    public function __toString();
}