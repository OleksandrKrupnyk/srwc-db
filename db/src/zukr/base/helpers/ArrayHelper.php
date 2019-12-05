<?php


namespace zukr\base\helpers;


class ArrayHelper
{


    public static function group($array, $groupKey): array
    {
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
    public static function asort(&$array)
    {
        $collator = new \Collator('uk_UA.UTF-8');
        $collator->asort($array);
    }

}