<?php


namespace zukr\work;


use zukr\base\Base;

/**
 * Class WorkHelper
 *
 * @package      zukr\work
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class WorkHelper
{
    /** @var WorkHelper */
    private static $obj;

    /** @var array */
    private static $works;

    /**
     * WorkHelper constructor.
     */
    private function __construct()
    {
        static::init();
    }

    /**
     * @return WorkHelper
     */
    public static function getInstance(): WorkHelper
    {
        if (static::$obj === null) {
            static::$obj = new self();
        }
        return static::$obj;

    }


    /**
     *
     */
    private static function init(): void
    {
        if (empty(static::$works)) {
            static::$works = Base::$app->cacheGetOrSet(
                'works_list',
                (new WorkRepository())->getAllWorksAsArray(),
                30);
        }
    }

    /**
     * @return array
     */
    public static function getWorks(): array
    {
        return static::$works;
    }

    /**
     * @return array
     */
    public function getAllWorks(): array
    {
        return static::$works;
    }

    /**
     * @return array
     */
    public function getInvitationWorks(): array
    {
        return \array_filter(self::getWorks(), static function ($value) {
            return $value['invitation'] === 1;
        });
    }

    /**
     * @return array
     */
    public function getTesisWorks(): array
    {
        return \array_filter(self::getWorks(), static function ($value) {
            return $value['tesis'] === 1;
        });
    }

    /**
     * @return array
     */
    public function getArrivalWorks(): array
    {
        return \array_filter(self::getWorks(), static function ($value) {
            return $value['arrival'] === 1;
        });
    }

    /**
     * @return array
     */
    public function getIntroductionWorks(): array
    {
        return \array_filter(self::getWorks(), static function ($value) {
            return !empty($value['introduction']);
        });
    }

    /**
     * @return array
     */
    public function getPublicWorks(): array
    {
        return \array_filter(self::getWorks(), static function ($value) {
            return !empty($value['public']);
        });
    }

    /**
     * @return array
     */
    public function getCommentsWorks(): array
    {
        return \array_filter(self::getWorks(), static function ($value) {
            return !empty($value['comments']);
        });
    }

    /**
     * @return array
     */
    public function getOrderByBallsWorks(): array
    {
        $array = self::getWorks();
        \usort($array, static function ($a, $b) {
            return $b['balls'] <=> $a['balls'];
        });
        return $array;
    }


}