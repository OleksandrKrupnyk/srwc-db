<?php


namespace zukr\user;

use zukr\base\RecordHelper;

/**
 * Class UserHelper
 *
 * @package      zukr\user
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class UserHelper extends RecordHelper
{

    /** @var UserHelper */
    private static $obj;

    private $adminIds;

    /**
     * @return UserHelper
     */
    public static function getInstance(): UserHelper
    {
        if (static::$obj === null) {
            static::$obj = new static();
        }
        return static::$obj;

    }

    /**
     * @return array Сисок ІД користувачів зі статусом АДМІН
     */
    public function getIdsAdmin():array
    {
        if($this->adminIds === null) {
            $adminIds = (new UserRepository())->getUserIdAsAdmin();
            $this->adminIds  = $adminIds;
        }
        return $this->adminIds;
    }
}