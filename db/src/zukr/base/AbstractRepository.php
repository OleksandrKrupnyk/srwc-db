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
    /** @var Record */
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

    //    /**
    //     * @param $name
    //     * @return mixed
    //     */
    //    public function __get($name)
    //    {
    //        if ($name === 'get' . static::getShortName()) {
    //            return self::getInstance();
    //        }
    //    }
    //
    //    public function __set($name, $value)
    //    {
    //    }
    //
    //    public function __isset($name)
    //    {
    //    }
    //
    //    /**
    //     * @return string Назва класу
    //     */
    //    private static function getShortName(): string
    //    {
    //        try {
    //            return (new \ReflectionClass($this->getClassName()))->getShortName();
    //        } catch (\ReflectionException $e) {
    //            var_dump($e->getMessage());
    //        }
    //    }
    //
    //
    //    /**
    //     * @return mixed
    //     */
    //    protected static function getInstance()
    //    {
    //
    //        if (static::$instance === null) {
    //            static::$instance = new ();
    //        }
    //        return static::$instance;
    //    }
    //
    //    /**
    //     * @return string
    //     */
    //    public function getClassName(): string
    //    {
    //        try {
    //            if (empty($this->__className)) {
    //                throw new InvalidConfigurationException(__CLASS__ . '::$__className must be set.');
    //            }
    //            return $this->__className;
    //        } catch (\Exception $e) {
    //            var_dump($e->getMessage());
    //        }
    //    }
    //
    //    /**
    //     * @param $id
    //     * @return mixed|null Модель з заповненими даними
    //     * @throws \Exception
    //     */
    //    public static function findById($id)
    //    {
    //        $instanceData = self::getById($id);
    ////        if (!empty($instanceData)) {
    ////            call_user_func([static::$instance,'load',[$instanceData, false]]);
    //////            static::$instance->load($instanceData, false);
    ////            return static::$instance;
    ////        }
    ////        return null;
    //    }
    //


}