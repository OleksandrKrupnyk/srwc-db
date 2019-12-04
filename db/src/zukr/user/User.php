<?php


namespace zukr\user;


use zukr\base\Record;

/**
 * Class User
 *
 * @package      zukr\user
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class User extends Record
{

    public $id;
    public $usr;
    public $pass;
    public $dt;

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'tz_members';
    }


}