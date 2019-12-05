<?php


namespace zukr\univer;

use zukr\base\helpers\ArrayHelper;

/**
 * Class UniverHelper
 *
 * @package      zukr\univer
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class UniverHelper
{

    /**
     * @param array $univers
     * @return array
     */
    public static function getDropDownListShotFull(array $univers): array
    {
        $list = [];
        foreach ($univers as $key => $u) {
            $list [$key] = '(' . $u['univer'] . ') ' . $u['univerfull'];
        }
        ArrayHelper::asort($list);
        return $list;

    }


}