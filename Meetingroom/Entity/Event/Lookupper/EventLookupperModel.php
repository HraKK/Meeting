<?php
namespace Meetingroom\Entity\Event\Lookupper;

use \Meetingroom\Entity\Event\Lookupper\Criteria;


class EventLookupperModel
{
    /**
     * @var array
     */
    protected $conditions = [];
    /**
     * @var Phalcon\DI
     */
    protected $di;
    /**
     * @var string
     */

    protected $db;


    /**
     * @param Phalcon\DI $di
     */
    public function __construct($di)
    {
        $this->di = $di;
        $this->db = $di->getShared('db');
    }


    /**
     * @param RoomCriteria $roomCriteria
     * @param PeriodCriteriaInterface $periodCriteria
     * @return array array of \Meetingroom\Entity\Event\Event
     */
    public function getEvents(
        \Meetingroom\Entity\Event\Lookupper\Criteria\RoomCriteria $roomCriteria,
        \Meetingroom\Entity\Event\Lookupper\Criteria\PeriodCriteriaInterface $periodCriteria
    ) {
        $sql = $this->buildQuery($roomCriteria, $periodCriteria);

        $result = $this->execute($sql);

        $list = [];
        foreach ($result as $id => $data) {

            $list[$id] = (new \Meetingroom\Entity\Event\EventEntity())->bind($data);
        }

        return $list;
    }


    protected function buildQuery(
        \Meetingroom\Entity\Event\Lookupper\Criteria\RoomCriteria $roomCriteria,
        \Meetingroom\Entity\Event\Lookupper\Criteria\PeriodCriteriaInterface $periodCriteria
    ) {
        $eventBuilder = new \Meetingroom\Entity\Event\Lookupper\Builder\EventBuilder();

        return $eventBuilder->build($roomCriteria, $periodCriteria);

    }

    /**
     * @param string $sql
     * @return array
     */
    protected function execute($sql)
    {

        $result = $this->db->query($sql);
        $result->setFetchMode(\Phalcon\Db::FETCH_ASSOC);

        return $result->fetchAll();
    }

} 