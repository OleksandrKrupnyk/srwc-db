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
     * @return User|null
     */
    public function getProfile(): array
    {
        if ($this->_profile === []) {
            $this->_profile = ($this->id !== 0)
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
        try {
            $profile = $this->getProfile();
            if (isset($profile['review'])) {
                return (int)$profile['review'] === self::KEY_ON;
            }
        } catch (InvalidArgumentException $e) {

        }
        return false;
    }
}