<?php


namespace zukr\univer;


class UniverRepository
{


    public function getDropList()
    {
        return Univer::find()
            ->map('id')
            ->get(Univer::getTableName(),null,'id,univerfull')
            ;
    }

    /**
     * @return array|\MysqliDb
     * @throws \Exception
     */
    public function getInvitedDropList()
    {
        return Univer::find()
            ->map('id')
            ->where('invite',1)
            ->get(Univer::getTableName(),null,['id',"concat('\(',univer,'\) ',univerfull) as univerfull"])
            ;
    }

}