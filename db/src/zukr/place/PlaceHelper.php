<?php


namespace zukr\place;

use zukr\base\RecordHelper;

/**
 * Class PlaceHelper
 *
 * @package      zukr\place
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class PlaceHelper extends RecordHelper
{

    /**
     * @var PlaceHelper
     */
    private static $obj;

    /**
     * @return PlaceHelper
     */
    public static function getInstance(): PlaceHelper
    {
        if (static::$obj === null) {
            static::$obj = new static();
        }
        return static::$obj;

    }

    /**
     * @param string $place
     * @return string
     */
    public function diplomString(string $place): string
    {
        $text = ['I' => 'ПЕРШОГО СТУПЕНЯ', 'II' => 'ДРУГОГО СТУПЕНЯ', 'III' => 'ТРЕТЬОГО СТУПЕНЯ'];
        return $text[$place] ?? 'ПУСТИЙ РЯДОК';
    }

    /**
     * @return array
     */
    public function getPlaceList(): array
    {
        return [
            'D' => 'D',
            'I' => 'I',
            'II' => 'II',
            'III' => 'III'
        ];
    }
}