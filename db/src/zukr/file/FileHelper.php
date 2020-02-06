<?php


namespace zukr\file;

use zukr\base\Base;
use zukr\base\helpers\ArrayHelper;
use zukr\base\RecordHelper;

/**
 * Class FileHelper
 *
 * @package      zukr\file
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class FileHelper extends RecordHelper
{

    /** @var FileHelper */
    private static $obj;

    /** @var array */
    private $files;


    /**
     * @return FileHelper
     */
    public static function getInstance(): FileHelper
    {
        if (static::$obj === null) {
            static::$obj = new static();
        }
        return static::$obj;

    }

    /**
     * @return array|\MysqliDb
     */
    public function getFiles()
    {
        if ($this->files === null) {
            $this->files = Base::$app->cacheGetOrSet(
                static::class,
                function () {
                    return $this->getFilesFromDB();
                },
                300);
        }
        return $this->files;
    }

    /**
     * @return array|\MysqliDb
     */
    public function getFilesFromDB()
    {
        try {
            return File::find()
                ->get(File::getTableName());
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }
    }

    /**
     * @param int $type
     * @return array
     */
    public function getFilesByType(int $type): array
    {
        if (!\in_array($type, File::getTypes())) {
            return [];
        }
        $files = $this->getFiles();
        return \array_filter($files, static function ($file) use ($type) {
            return $file['typeoffile'] === $type;
        });
    }

    /**
     * @return array Список файлів робіт сгрупованих по ІД запису роботи
     */
    public function getAllWorkFilesIndexByWorkId()
    {
        return ArrayHelper::group($this->getFilesByType(File::TYPE_WORK), 'id_w');
    }

    /**
     * @param int $workId ІД роботи
     * @return array список файлів роботи
     */
    public function getFilesOneWork(int $workId): array
    {
        return $this->getAllWorkFilesIndexByWorkId()[$workId] ?? [];
    }

    /**
     * @return array Список файлів тезисів сгрупованих по ІД запису роботи
     */
    public function getAllTesisFilesIndexByWorkId()
    {
        return ArrayHelper::group($this->getFilesByType(File::TYPE_TESIS), 'id_w');
    }

    /**
     * @return array Список файлів усіх типів сгрупованих по ІД запису роботи
     */
    public function getAllFilesIndexByWorkId()
    {
        return ArrayHelper::group($this->getFiles(), 'id_w');
    }
}