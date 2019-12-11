<?php


namespace zukr\univer;

use zukr\base\Base;
use zukr\base\helpers\ArrayHelper;
use zukr\work\WorkHelper;

/**
 * Class UniverHelper
 *
 * @package      zukr\univer
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class UniverHelper
{

    /** @var UniverHelper */
    private static $obj;

    /** @var array */
    private $univers;
    /** */
    private $univerRepository;

    /**
     * WorkHelper constructor.
     */
    private function __construct()
    {
        $this->univerRepository = new UniverRepository();
    }

    /**
     * @return UniverHelper
     */
    public static function getInstance(): UniverHelper
    {
        if (static::$obj === null) {
            static::$obj = new self();
        }
        return static::$obj;

    }


    /**
     * @param array $univers
     * @return array
     */
    public function getDropDownListShotFull(array $univers): array
    {
        $list = [];
        foreach ($univers as $key => $u) {
            $list [$key] = '(' . $u['univer'] . ') ' . $u['univerfull'];
        }
        ArrayHelper::asort($list);
        return $list;
    }

    /**
     * @return array
     */
    public function getAllUniversFromDB(): array
    {
        return $this->univerRepository->getAllUniversAsArrayFromDB();
    }

    /**
     * @return array|mixed
     */
    protected function getUnivers(): array
    {
        if ($this->univers === null) {
            $univers = Base::$app->cacheGetOrSet(get_called_class(), $this->getAllUniversFromDB(), 360);
            $this->univers = $univers;
        }
        return $this->univers;
    }

    /**
     * @return array
     */
    public function getInvitedUnivers(): array
    {
        return array_filter($this->getUnivers(),
            static function ($univer) {
                return (int)$univer['invite'] === 1 || $univer['id'] === 1;
            });
    }

    /**
     * @return array|\MysqliDb
     */
    public function getInvitedDropdownList(): array
    {
        return $this->getDropDownListShotFull(
            $this->getInvitedUnivers()
        );

    }

    /**
     *
     */
    public function getTakePartUniversDropDownList()
    {
        $wh = WorkHelper::getInstance();
        $univerIds = $wh->getTakePartUniversIds();
        $list = [];
        $invitedUnivers = $this->getInvitedUnivers();
        foreach ($univerIds as $univerId) {
            $list[$univerId] = $invitedUnivers[$univerId];
        }
        return $this->getDropDownListShotFull(
            $list);

    }

}