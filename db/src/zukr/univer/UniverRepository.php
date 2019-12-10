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
     * @return array|\MysqliDb
     */
    public function getAllUniversAsArrayFromDB(): array
    {
        try {
            return Univer::find()
                ->map('id')
                ->orderBy('univerfull')
                ->get(Univer::getTableName());

        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }
    }
}