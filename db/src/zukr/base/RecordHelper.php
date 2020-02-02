<?php


namespace zukr\base;

use ReflectionClass;

/**
 * Class RecordHelper
 *
 * @package      zukr\base
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
abstract class RecordHelper
{

    /**
     * RecordHelper constructor.
     */
    protected final function __construct()
    {
    }

    /**
     * @param string $str Назва файлу
     * @return string
     */
    public function registerJS($str)
    {
        $filename = $this->getDir() . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . $str;
        $fileContent = \file_exists($filename) && \is_file($filename)
            ? '<script>' . \file_get_contents($filename) . '</script>'
            : '';
        return $fileContent;
    }

    /**
     * @return string Поточна директорія нащадка
     */
    protected final function getDir(): string
    {
        try {
            $dir = \dirname((new ReflectionClass(static::class))->getFileName());
        } catch (\ReflectionException $e) {
            $dir = __DIR__;
        }
        return $dir;
    }

}