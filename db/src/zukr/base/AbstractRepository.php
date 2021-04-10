<?php


namespace zukr\base;


/**
 * Class AbstractRepository
 *
 * @package      zukr\base
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
abstract class AbstractRepository implements BaseRepositoryInterface
{
    /**
     * @var Record
     */
    protected $model;
    /**
     * @var string|Record Назва класу
     */
    protected $__className;

    /**
     * AbstractRepository constructor.
     */
    public function __construct()
    {
        $this->model = new $this->__className();
    }

    /**
     * @param $id
     * @return RecordInterface|null
     */
    public function findById($id)
    {
        $data = $this->getById($id);
        if (!empty($data)) {
            /** @var Record $record */
            $record = clone $this->model;
            $record->load($data, false);
            return $record;
        }
        return null;
    }

    /**
     * @param $id
     * @return array
     */
    public function getById($id): ?array
    {
        return $this->model->findById($id);
    }

}