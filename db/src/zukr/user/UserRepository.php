<?php


namespace zukr\user;


use zukr\base\AbstractRepository;
use zukr\base\Base;

/**
 * Class UserRepository
 *
 * @package      zukr\user
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class UserRepository extends AbstractRepository
{

    /**
     * @var array
     */
    private $users;

    /** @var string */
    protected $__className = User::class;

    /**
     * @return array|\MysqliDb
     */
    public function getDropDownList()
    {
        if ($this->users === null) {
            $users = Base::$app->cacheGetOrSet(User::class,
                function () {
                    return $this->getUsersFormDB();
                },
                60);
            $this->users = $users;
        }
        return $this->users;
    }

    /**
     * @return array|\MysqliDb
     */
    private function getUsersFormDB()
    {
        try {
            $users = User::find()
                ->map('id')
                ->where('usr', 'AJAX', '<>')
                ->get(User::getTableName(), null, ['id', 'usr']);
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
        }

        $users[0] = 'not set';
        \ksort($users);
        return $users;
    }

    /**
     * @return array
     */
    public function getUserIdAsAdmin(): array
    {
        try {
            return User::find()
                ->where('is_admin', '1', '=')
                ->getValue(User::getTableName(), 'id', null);
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }
    }

}