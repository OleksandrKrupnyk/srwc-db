<?php


namespace zukr\file;


use zukr\base\AbstractRepository;

/**
 * Class FileRepository
 *
 * @package      zukr\file
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class FileRepository extends AbstractRepository
{
    /** @var string */
    public $__className = File::class;

    /**
     * @param $id
     * @return \zukr\base\Record|\zukr\base\RecordInterface|null|File
     */
    public function findById($id)
    {
        return parent::findById($id);
    }


    /**
     * @param $guid
     * @return array|\MysqliDb|string
     * @throws \Exception
     */
    public function getByGuid($guid)
    {
        return $this->model->getDb()
            ->where('guid', $guid)
            ->getOne($this->model::getTableName());
    }

    /**
     * @param string $guid
     * @return File|null
     */
    public function findByGuid(string $guid)
    {
        $data = $this->getByGuid($guid);
        if (!empty($data)) {
            /** @var File $record */
            $record = clone $this->model;
            $record->load($data, false);
            return $record;
        }
        return null;
    }
}