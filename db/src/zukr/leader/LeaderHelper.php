<?php


namespace zukr\leader;


use zukr\base\Base;
use zukr\base\helpers\ArrayHelper;
use zukr\base\helpers\PersonHelper;
use zukr\base\html\HtmlHelper;
use zukr\base\RecordHelper;
use zukr\position\PositionRepository;
use zukr\workleader\WorkLeader;
use zukr\workleader\WorkLeaderRepository;

/**
 * Class LeaderHelper
 *
 * @package      zukr\leader
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class LeaderHelper extends RecordHelper
{

    /**
     * @var LeaderHelper
     */
    private static $obj;

    /**
     * @var array
     */
    private $worksLeaders;
    /**
     * @var array
     */
    private $leaders;
    /**
     * @var array
     */
    private $leadersOfWork;

    /**
     * @var LeaderRepository
     */
    private $leaderRepository;
    /**
     * @var WorkLeaderRepository
     */
    private $workLeaderRepository;

    /**
     * @return LeaderHelper
     */
    public static function getInstance(): LeaderHelper
    {
        if (static::$obj === null) {
            static::$obj = new static();
        }
        return static::$obj;

    }

    /**
     * @param int $workId ІД роботи
     *
     * @return array|mixed
     */
    public function getLeadersByWorkId($workId)
    {
        if ($this->leadersOfWork === null) {
            $worksLeaders = $this->getCachedWorksLeaders();
            $this->leadersOfWork = ArrayHelper::group($worksLeaders, 'id_w');
        }
        return $this->leadersOfWork[$workId] ?? [];
    }

    /**
     * @return array|\MysqliDb
     */
    protected function getCachedWorksLeaders()
    {
        if ($this->worksLeaders === null) {
            $this->worksLeaders = Base::$app->cacheGetOrSet(
                WorkLeader::class,
                function () {
                    return $this->getWorkLeaderRepository()->getAllLeadersOfWorks();
                },
                360
            );
        }
        return $this->worksLeaders;
    }

    /**
     * @param int $univerId ІД університету
     * @return array
     */
    public function getAllLeadersByUniverId(int $univerId): array
    {
        $leaders = $this->getLeaders();
        if (!empty($leaders)) {
            return \array_filter($leaders, static function ($leader) use ($univerId) {
                return $leader['id_u'] === $univerId;
            });
        }
        return [];
    }

    /**
     * @return int Кількість запрощених керівників робіт
     */
    public function getCountInvitationLeaders(): int
    {
        return $this->getLeaderRepository()->getCountInvitedLeaders();
    }

    /**
     * @param int $univerId ІД університету
     * @return array Список усіх запрошених керівників
     */
    public function getAllInvitationLeadersByUniverId(int $univerId): array
    {
        $leaders = $this->getLeaderRepository()->getAllByUniverId($univerId);
        if (!empty($leaders)) {
            return \array_filter($leaders, static function ($leader) {
                return $leader['invitation'] === Base::KEY_ON;
            });
        }
        return [];
    }

    /**
     * @return LeaderRepository
     */
    public function getLeaderRepository(): LeaderRepository
    {
        if ($this->leaderRepository === null) {
            $this->leaderRepository = new LeaderRepository();
        }
        return $this->leaderRepository;
    }

    /**
     * @param array $leaders Список керівників
     * @return string
     */
    public function listWithCheckBox(array $leaders): string
    {
        $list = [];
        $positions = (new PositionRepository())->getDropDownList();
        foreach ($leaders as $leader) {
            $item = HtmlHelper::checkbox('invitation', '', $leader['invitation']) . PersonHelper::getFullName($leader) . ', ' . $positions[$leader['id_pos']];
            $list[] = '<li data-key="' . $leader['id'] . '">' . $item . '</li>';
        }
        return '<ol>' . \implode('', $list) . '</ol>';

    }

    /**
     * @return WorkLeaderRepository
     */
    public function getWorkLeaderRepository(): WorkLeaderRepository
    {
        if ($this->workLeaderRepository === null) {
            $this->workLeaderRepository = new WorkLeaderRepository();
        }
        return $this->workLeaderRepository;
    }

    /**
     * @return array|mixed
     */
    public function getLeaders()
    {
        if ($this->leaders === null) {
            $this->leaders = $this->getLeaderRepository()->getAllLeaders();
        }
        return $this->leaders;
    }
}