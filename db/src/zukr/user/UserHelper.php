<?php


namespace zukr\user;


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
     * @return array
     */
    public function getIsAdmin():array
    {
        if($this->adminIds === null)
        {
            $adminIds = (new UserRepository())->getUserIdAsAdmin();
            $this->adminIds  = $adminIds;
        }
        return $this->adminIds;
    }
}