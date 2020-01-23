<?php


namespace zukr\base\helpers;

/**
 * Class TextHelper
 *
 * @package      zukr\base\helpers
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class TextHelper
{
    /**
     * Формирует окончание предложения для письма во 2-м инф. приглашении
     *  В зависомости от количества работ изменяет окончание предложения
     *
     * @param int $amountOfWorks
     * @return string
     */
    public static function declensionWork(int $amountOfWorks)
    {
        if (\in_array($amountOfWorks, [1, 21, 31])):
            $str = 'роботу';
        elseif (\in_array($amountOfWorks, [2, 3, 4, 22, 23, 24, 32, 263])):
            $str = 'роботи';
        else:
            $str = 'робіт';
        endif;

        return $amountOfWorks . '&nbsp;' . $str;
    }
}