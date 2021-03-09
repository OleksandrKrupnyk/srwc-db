<?php


namespace zukr\scanfiles;


use zukr\base\AbstractRepository;
use zukr\base\Base;
use zukr\univer\Univer;

/**
 * Class ScanFilesRepository
 *
 * @package zukr\scanfiles
 * @author Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class ScanFilesRepository extends AbstractRepository
{
    /**
     * @var string
     */
    protected $__className = ScanFiles::class;

    /**
     * Список усіх відсканованих запрошень для журі
     *
     * @return array|\MysqliDb|string
     */
    public function getAllScannedFiles(): array
    {
        $joinTable = Univer::getTableName();
        $table = $this->model::getTableName();
        try {
            return $this->model::find()
                ->join($joinTable, $table . '.id_u=' . $joinTable . '.id')
                ->get($table);
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }
    }
}