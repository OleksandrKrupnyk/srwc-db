<?php


namespace zukr\user;


use zukr\base\AuthInterface;
use zukr\base\exceptions\InvalidArgumentException;
use zukr\base\Record;
use zukr\leader\LeaderRepository;

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
    /** @var array */
    private $_profile = [];
    private $_isReview;

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'tz_members';
    }


    public final function isGuest(): bool
    {
        return false;
    }


    public function isAdmin(): bool
    {
        return (bool)$this->is_admin;
    }

    public function getLogin(): string
    {
        return $this->usr;
    }

    /**
     * @return array
     */
    public function getProfile(): array
    {
        if ($this->_profile === null) {
            $this->_profile = $this->id !== 0
                ? (new LeaderRepository())->getByTzMemberId($this->id)
                : [];
        }
        return $this->_profile;
    }

    /**
     * @return bool
     */
    public function isReview(): bool
    {
        if ($this->_isReview === null) {
            $profile = $this->getProfile();
            $this->_isReview = isset($profile['review'])
                ? (int)$profile['review'] === self::KEY_ON
                : false;
        }
        return $this->_isReview;

    }
}