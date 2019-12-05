<?php


namespace zukr\univer;

use zukr\base\Base;

/**
 * Class UniverRepository
 *
 * @package      zukr\univer
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class UniverRepository
{
    /**
     * @var array
     */
    private $_univers;

    public function getDropList()
    {
        return $this->getUnivers();
    }

    /**
     * @return array|\MysqliDb
     */
    public function getInvitedDropList(): array
    {
        return UniverHelper::getDropDownListShotFull(
            $this->getUnivers()
        );

    }

    /**
     * @return array|\MysqliDb
     */
    public function getAllInvitedAsArrayFromDB(): array
    {
        try {
            return Univer::find()
                ->map('id')
                ->orWhere('id', 1)
                ->orWhere('invite', 1)
                ->get(Univer::getTableName());
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }
    }

    /**
     * @return array|mixed
     */
    private function getUnivers(): array
    {

        if ($this->_univers === null) {
            $this->_univers = Base::$app->cacheGetOrSet(get_called_class(), $this->getAllInvitedAsArrayFromDB(), 300);
        }
        return $this->_univers;
    }
}