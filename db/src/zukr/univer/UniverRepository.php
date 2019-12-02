<?php


namespace zukr\univer;

/**
 * Class UniverRepository
 *
 * @package      zukr\univer
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class UniverRepository
{


    public function getDropList()
    {
        return Univer::find()
            ->map('id')
            ->get(Univer::getTableName(), null, 'id,univerfull');
    }

    /**
     * @return array|\MysqliDb
     * @throws \Exception
     */
    public function getInvitedDropList()
    {
        return Univer::find()
            ->map('id')
            ->where('invite', 1)
            ->get(Univer::getTableName(), null, ['id', "concat('\(',univer,'\) ',univerfull) as univerfull"]);
    }

    /**
     * @return array|\MysqliDb
     * @throws \Exception
     */
    public function getAllInvitedAsArray()
    {
        return Univer::find()
            ->map('id')
            ->orWhere('id',1)
            ->orWhere('invite', 1)
            ->get(Univer::getTableName());
    }

}