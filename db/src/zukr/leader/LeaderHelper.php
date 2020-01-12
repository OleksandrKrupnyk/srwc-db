<?php


namespace zukr\leader;


use zukr\base\helpers\ArrayHelper;
use zukr\base\helpers\PersonHelper;
use zukr\base\html\HtmlHelper;
use zukr\position\PositionRepository;
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

    /**
     * @var array
     */
    private $worksLeaders;
    /**
     * @var array
     */
    private $leadersOfWork;

    /**
     * @var LeaderRepository
     */
    private $leaderRepository;

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
            $this->worksLeaders = (new WorkLeaderRepository())->getAllLeadersOfWorks();
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
     * @param int $univerId
     * @return array
     */
    public function getAllInvitationLeadersByUniverId(int $univerId): array
    {
        $leaders = $this->getLeaderRepository()->getAllByUniverId($univerId);
        if (!empty($leaders)) {
            return \array_filter($leaders, static function ($leader) {
                return $leader['invitation'] === 1;
            });
        }
        return [];
    }

    /**
     * @return string
     */
    public function registerJS(): string
    {
        $filename = __DIR__ . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'leader.js';
        $fileContent = \file_exists($filename) && \is_file($filename)
            ? '<script>' . \file_get_contents($filename) . '</script>'
            : '';
        return $fileContent;
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
}