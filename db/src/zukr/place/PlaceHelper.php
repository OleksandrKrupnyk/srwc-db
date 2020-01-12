<?php


namespace zukr\place;

/**
 * Class PlaceHelper
 *
 * @package      zukr\place
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class PlaceHelper
{

    /**
     * @var PlaceHelper
     */
    private static $obj;


    /**
     * SectionHelper constructor.
     */
    private function __construct()
    {
    }

    /**
     * @return PlaceHelper
     */
    public static function getInstance(): PlaceHelper
    {
        if (static::$obj === null) {
            static::$obj = new self();
        }
        return static::$obj;

    }

    /**
     * @return string
     */
    public function registerJS()
    {
        $filename = __DIR__ . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'place.js';
        $fileContent = \file_exists($filename) && \is_file($filename)
            ? '<script>' . \file_get_contents($filename) . '</script>'
            : '';
        return $fileContent;
    }
}