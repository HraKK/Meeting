<?php
namespace Meetingroom\Entity\Event\Lookupper;


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
    protected $query;


    /**
     * @param Phalcon\DI $di
     */
    public function __construct($di)
    {

        $this->di = $di;

    }

    /**
     * Add single criteria
     *
     * @param array $conditions array of Criteria\LookupperCriteriaInterface
     * @return void
     */
    public function setCriterias(array $conditions)
    {
        $this->conditions = $conditions;
    }

    /**
     * Set array of criterias
     *
     * @param Criteria\LookupperCriteriaInterface $condition
     * @return void
     */
    public function addCriterias(Criteria\LookupperCriteriaInterface $condition)
    {
        $this->conditions[] = $condition;
    }

    /**
     * @return array array of \Meetingroom\Entity\Event\Event
     */
    public function getEvents()
    {
        $result = $this->execute($this->buildQuery());

        foreach ($result as $id => $data) {
            $list[$id] = (new \Meetingroom\Entity\Event\Event())->bind($data);
        }

        return $list;
    }


    protected function buildQuery()
    {
        $where_str = implode(' AND ', $this->conditions);
        $this->query = "SELECT * FROM events WHERE 1=1 AND " . $where_str;
    }

    /**
     * @param string $sql
     * @return array
     */
    protected function execute($sql)
    {
        $result = $this->db->query($this->query);
        $result->setFetchMode(\Phalcon\Db::FETCH_ASSOC);

        return $result;
    }

} 