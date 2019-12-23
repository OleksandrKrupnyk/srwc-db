<?php


namespace zukr\leader;


use zukr\base\helpers\ArrayHelper;
use zukr\workleader\WorkLeaderRepository;

/**
 * Class LeaderHelper
 *
 * @package      zukr\leader
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class LeaderHelper
{

    /** @var LeaderHelper */
    private static $obj;

    /** @var array */
    private $worksLeaders;
    /** @var array */
    private $leadersOfWork;


    /**
     * LeaderHelper constructor.
     */
    private function __construct()
    {

    }

    /**
     * @return LeaderHelper
     */
    public static function getInstance(): LeaderHelper
    {
        if (static::$obj === null) {
            static::$obj = new self();
        }
        return static::$obj;

    }

    /**
     * @param $workId
     * @return array|mixed
     */
    public function getLeadersByWorkId($workId)
    {
        if ($this->leadersOfWork == null) {
            $worksLeaders = $this->getWorksLeaders();
            $this->leadersOfWork = ArrayHelper::group($worksLeaders, 'id_w');
        }
        return $this->leadersOfWork[$workId] ?? [];
    }

    /**
     * @return array|\MysqliDb
     */
    protected function getWorksLeaders()
    {
        if ($this->worksLeaders === null) {
            $worksLeaders = (new WorkLeaderRepository())->getAllLeadersOfWorks();
            $this->worksLeaders = $worksLeaders;
        }
        return $this->worksLeaders;
    }

    /**
     * @param int $univerId
     * @return array
     */
    public function getAllLeadersByUniverId(int $univerId): array
    {
        $leaders = $this->getWorksLeaders();
        if (!empty($leaders)) {
            return array_filter($this->getWorksLeaders(), function ($author) use ($univerId) {
                return $author['id_u'] === $univerId;
            });
        }
        return [];
    }

    /**
     * @return int Кількість запрощених керівників робіт
     */
    public function getCountInvitationLeaders(): int
    {
        return (new LeaderRepository())->getCountInvitedLeaders();
    }

    /**
     * @param array $d
     * @return string
     */
    public function getFullName(array $d): string
    {
        if (empty($d)) {
            return '';
        }
        $items[] = $d['suname'] ?? '';
        $items[] = $d['name'] ?? '';
        $items[] = $d['lname'] ?? '';
        return \trim(\implode(' ', $items));

    }

}