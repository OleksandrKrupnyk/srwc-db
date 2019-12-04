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
     * @throws \Exception
     */
    public function getDropDownList()
    {
        if ($this->users === null) {
            $users = Base::$app->cacheGetOrSet(get_called_class(), $this->getUsersFormDB(), 600);
            $this->users = $users;
        }
        return $this->users;
    }

    /**
     * @return array|\MysqliDb
     * @throws \Exception
     */
    private function getUsersFormDB()
    {
        $users = User::find()
            ->map('id')
            ->where('usr', 'AJAX', '<>')
            ->get(User::getTableName(), null, ['id', 'usr']);

        $users[0] = 'not set';
        ksort($users);
        return $users;
    }

}