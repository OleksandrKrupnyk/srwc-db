<?php


namespace zukr\user;

/**
 * Class UserHelper
 *
 * @package      zukr\user
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class UserHelper
{

    /** @var UserHelper */
    private static $obj;

    /** @var array */
    private $users;

    private $adminIds;
    /**
     * LeaderHelper constructor.
     */
    private function __construct()
    {

    }

    /**
     * @return UserHelper
     */
    public static function getInstance(): UserHelper
    {
        if (static::$obj === null) {
            static::$obj = new self();
        }
        return static::$obj;

    }

    /**
     * @return array Сисок ІД користувачів зі статусом АДМІН
     */
    public function getIdsAdmin():array
    {
        if($this->adminIds === null)
        {
            $adminIds = (new UserRepository())->getUserIdAsAdmin();
            $this->adminIds  = $adminIds;
        }
        return $this->adminIds;
    }
}