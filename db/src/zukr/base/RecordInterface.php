<?php


namespace zukr\base;

/**
 * Interface RecordInterface
 *
 * @package zukr\base
 */
interface RecordInterface
{

    public function beforeSave();
    public function afterSave();
    public static function getPrimaryKey();
}