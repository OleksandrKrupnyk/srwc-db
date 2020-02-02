<?php


namespace zukr\base;

use MysqliDb;

/**
 * Class Record
 *
 * @package zukr\base
 */
abstract class Record implements RecordInterface
{
    /**
     * Інформувати користувача про зміні в записі
     */
    protected const NOTIFICATION_ACTIONS = true;
    public const    KEY_ON               = 1;
    public const    KEY_OFF              = 0;
    /**
     * @var MysqliDb
     */
    private $_db;
    private $_actionSave;
    public  $_isNewRecord;

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $primaryKeyId = static::getPrimaryKey();
        if ($this->{$primaryKeyId} === null) {
            $this->_actionSave = 'insert';
            $this->_isNewRecord = true;
        } else {
            $this->_actionSave = 'update';
            $this->_isNewRecord = false;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function afterSave(): bool
    {
        try {
            if ($this->_isNewRecord) {
                $this->{self::getPrimaryKey()} = $this->_db->getInsertId();
                $this->_isNewRecord = false;
            }
            return true;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * @return string
     */
    public static function getPrimaryKey(): string
    {
        return 'id';
    }

    /**
     * @return MysqliDb
     */
    public function getDb()
    {
        return $this->_db;
    }


    public function __construct()
    {
        $this->_db = Base::$app->db;
    }

    /**
     * @return string Назва моделі
     */
    public function getNameModel(): string
    {
        try {
            return (new \ReflectionClass($this))->getShortName();
        } catch (\ReflectionException $e) {
            Base::$log->error($e->getMessage());
            return 'noName';
        }
    }

    /**
     * Повертаэ назву таблиці
     *
     * @return string  Назва таблиці
     */
    public static function getTableName(): string
    {
        return \strtolower(\basename(static::class));
    }


    /**
     * ```php
     *
     *  load($_POST),
     *
     *  load(['a'=>1],false)
     *
     * ```
     * @param array            $arrayData
     * @param bool|string|null $form
     */
    public function load(array $arrayData, $form = null)
    {
        $data = $arrayData;
        if ($form === null) {
            $form = $this->getNameModel();
            $data = $arrayData[$form];
        } elseif ($form === false) {
            $data = $arrayData;
        } elseif (\is_string($form)) {
            $data = $arrayData[$form];
        }

        if (!empty($data)) {
            foreach ($data as $field => $value) {
                $this->{$field} = ($form === false) ? $value : \trim(\addslashes($value));
            }
        }

    }

    /**
     * @param array|int|string $id
     * @return MysqliDb|array|null
     */
    public function findById($id): ?array
    {
        try {
            if (static::getPrimaryKey() === 'id') {
                if (\filter_var($id, FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY)) {
                    return $this->_db
                        ->where(static::getPrimaryKey(), $id, 'IN')
                        ->get(static::getTableName());
                }
                if (\filter_var($id, FILTER_VALIDATE_INT)) {
                    return $this->_db->where(static::getPrimaryKey(), $id)
                        ->getOne(static::getTableName());
                }
            } else {

                if (\filter_var($id, FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY)) {
                    return $this->_db
                        ->where(static::getPrimaryKey(), $id, 'IN')
                        ->get(static::getTableName());
                }
                if (\filter_var($id, FILTER_SANITIZE_STRING)) {
                    return $this->_db->where(static::getPrimaryKey(), $id)
                        ->getOne(static::getTableName());
                }
            }
            return null;
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return null;
        }
    }

    /**
     * @return bool Результат виконання операції
     */
    public function save(): bool
    {
        $save = false;
        try {
            if (!$this->beforeSave()) {
                return false;
            }
            $this->_db->startTransaction();
            $save = $this->{$this->_actionSave}();

            $save = $save && $this->afterSave();
            ($save) ? $this->_db->commit() : $this->_db->rollback();
            if ($save && static::NOTIFICATION_ACTIONS) {
                Base::$session->setFlash('recordSaveMsg', 'Запис був збережений');
                Base::$session->setFlash('recordSaveType', 'info');
            }
        } catch (\Exception $e) {
            Base::$log->critical($e->getMessage());
        }
        return $save;
    }

    /**
     * @return MysqliDb
     */
    public static function find()
    {
        $className = static::class;
        return (new $className())->getDb();
    }

    /**
     * @return bool
     * @throws \Exception
     */
    protected function update(): bool
    {
        $arrayAttributes = $this->setAttributes();
        $primaryKeyId = static::getPrimaryKey();
        return $this->_db->where($primaryKeyId, $this->{$primaryKeyId})
            ->update(static::getTableName(), $arrayAttributes);
    }

    /**
     * @return bool|string
     * @throws \Exception
     */
    protected function insert()
    {
        $arrayAttributes = $this->setAttributes();
        $id = $this->_db->insert(static::getTableName(), $arrayAttributes);
        if ($id) {
            $primaryKeyId = static::getPrimaryKey();
            $this->{$primaryKeyId} = $id;
            return true;
        }
        return $this->_db->getLastError();
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function setAttributes()
    {
        $attributes = [];
        $dateTimeUpdate = $this->dateTimeUpdate();
        foreach ($this as $field => $value) {
            if ($value !== null && !is_object($value) && $field[0] !== '_') {
                if (\in_array($field, $dateTimeUpdate, true)) {
                    $attributes[$field] = $this->_db->now();
                } else {
                    $attributes[$field] = $value;
                }
            }

        }
        return $attributes;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        $attributes = [];
        foreach ($this as $field => $value) {
            if ($value !== null && !is_object($value) && $field[0] !== '_') {
                $attributes[$field] = $value;
            }
        }
        return $attributes;
    }

    /**
     * @param MysqliDb $db
     * @return bool
     * @throws \Exception
     */
    public function delete(MysqliDb $db): bool
    {
        $result = false;
        if ($db instanceof MysqliDb) {
            $result = (bool)$db->delete(static::getTableName());
        }
        return $result;
    }

    /**
     * @return array Список полів, в яких необхідно оновити дату створення/редагування
     */
    public function dateTimeUpdate(): array
    {
        return ['date'];
    }

}