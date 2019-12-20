<?php


namespace zukr\user;


use zukr\base\AuthInterface;
use zukr\base\Record;

/**
 * Class User
 *
 * @package      zukr\user
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class User extends Record implements AuthInterface
{

    public $id;
    public $usr;
    public $pass;
    public $dt;
    public $is_admin = 0;

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'tz_members';
    }


    public final function isGuest(): bool
    {
        false;
    }


    public function isAdmin(): bool
    {
        return (bool)$this->is_admin;
    }

    public function getLogin(): string
    {
        return $this->usr;
    }
}