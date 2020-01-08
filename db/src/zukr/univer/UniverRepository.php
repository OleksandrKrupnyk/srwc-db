<?php


namespace zukr\univer;

use zukr\base\AbstractRepository;
use zukr\base\Base;
use zukr\work\Work;

/**
 * Class UniverRepository
 *
 * Репозиторій доступу до записів університетів
 *
 * @package      zukr\univer
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class UniverRepository extends AbstractRepository
{
    /**
     * @var string
     */
    protected $__className = Univer::class;

    /**
     * @return array|\MysqliDb
     */
    public function getAllUniversAsArrayFromDB(): array
    {
        try {
            return Univer::find()
                ->map('id')
                ->orderBy('univerfull', 'ASC')
                ->get(Univer::getTableName());

        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }
    }

    /**
     * Список університетів, що надіслали роботи
     *
     * @return array Список університетів
     */
    public function getUniversWhoSentWorks(): array
    {
        try {
            $univerIds = $this->getUniversIdWhoSendWork();
            return Univer::find()->map('id')
                ->where('id',
                    $univerIds
                    , 'in')
                ->get(Univer::getTableName());
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }

    }

    /**
     * ІД записів університетів, що надіслали  роботи на участь
     *
     * @return array|mixed Список ІД університетів
     */
    public function getUniversIdWhoSendWork(): array
    {
        try {
            return Work::find()
                ->setQueryOption(['DISTINCT'])
                ->where('invitation', Work::KEY_ON)
                ->getValue(Work::getTableName(), 'id_u', null);
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }
    }

}