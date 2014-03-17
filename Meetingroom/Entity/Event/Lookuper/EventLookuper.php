<?php
namespace Meetingroom\Entity\Event\Lookuper;

class EventLookuper implements \Meetingroom\Entity\Event\Lookuper\EventLookuperInterface
{


    /**
     * @var array holder where conditions
     */
    protected $conditions = [];
    protected $di, $db;

    public function __construct($di)
    {
        $this->di = $di;
        $this->db = $this->di->getShared('db');
    }

    /**
     * @param Meetingroom\Entity\Event\Lookuper\RoomCriteriaInterface
     *
     * @return void
     */
    public function setRoomCriteria(Meetingroom\Entity\Event\Lookuper\RoomCriteriaInterface $criteria)
    {
        $this->conditions[] = $criteria;
    }

    /**
     * @param Meetingroom\Entity\Event\Lookuper\PeriodCriteriaInterface
     *
     * @return void
     */
    public function setPeriodCriteria(Meetingroom\Entity\Event\Lookuper\PeriodCriteriaInterface $criteria)
    {
        $this->conditions[] = $criteria;
    }

    /**
     *Return search result
     *
     * @return array of Meetingroom\Entity\Event\Event
     */
    public function lookup()
    {

        $where_str = implode(' AND ', $this->conditions);

        $result = $this->db->query("SELECT * FROM events WHERE 1=1 AND " . $where_str);
        $result->setFetchMode(\Phalcon\Db::FETCH_ASSOC);


        foreach ($result as $id => $data) {
            $list[$id] = (new \Meetingroom\Entity\Event\Event())->bind($data);
        }

        return $list;
        //TODO: add repeatable logic!
    }


}