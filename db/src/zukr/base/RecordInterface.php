<?php


namespace zukr\base;

/**
 * Interface RecordInterface
 *
 * @package zukr\base
 */
interface RecordInterface
{

    /**
     * @return mixed
     */
    public function beforeSave();

    /**
     * @return mixed
     */
    public function afterSave();

    /**
     * @return string
     */
    public static function getPrimaryKey();

    /**
     * Повертає назву таблиці
     *
     * @return string  Назва таблиці
     */
    public static function getTableName(): string;
}