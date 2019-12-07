<?php


namespace zukr\base\helpers;

/**
 * Class ArrayHelper
 *
 * @package      zukr\base\helpers
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class ArrayHelper
{

    /**
     * @param array  $array
     * @param string $groupKey
     * @return array
     */
    public static function group(array $array, string $groupKey): array
    {
        if (empty($array)) {
            return [];
        }
        $resultArray = [];
        foreach ($array as $item) {
            $key = $item[$groupKey];

            $resultArray[$key][] = $item;
        }
        return $resultArray;
    }

    /**
     * @param $array
     */
    public static function asort(&$array): void
    {
        $collator = new \Collator('uk_UA.UTF-8');
        $collator->asort($array);
    }

}