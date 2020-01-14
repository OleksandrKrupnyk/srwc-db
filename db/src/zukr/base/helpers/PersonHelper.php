<?php


namespace zukr\base\helpers;

/**
 * Class PersonHelper
 *
 * @package      zukr\base\helpers
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class PersonHelper
{
    /**
     * @param array $person
     * @return string
     */
    public static function getFullName(array $person): string
    {
        return \vsprintf('%s %s %s', [$person['suname'], $person['name'], $person['lname']]);
    }

    /**
     * @param array $person
     * @return string
     */
    public static function getShortName(array $person): string
    {
        return $person['suname'] . ' '
            . \mb_substr($person['name'], 0, 1, 'UTF-8') . '.'
            . \mb_substr($person['lname'], 0, 1, 'UTF-8') . '.';
    }

    /**
     *  Возвращает строку студент (студентка в звсисимости от окончания отчества)
     *
     * @param string $O
     * @return string
     */
    public static function student_ka($O): string
    {
        return StringHelper::right($O, 1) === 'ч'
            ? 'студент'
            : 'студентка';
    }

}