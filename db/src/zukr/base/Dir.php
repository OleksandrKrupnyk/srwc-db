<?php


namespace zukr\base;

/**
 * Class Dir
 *
 * @package      zukr\base
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class Dir
{
    private static $obj;

    /**
     * @return Dir
     */
    public static function getInstance(): self
    {
        if (self::$obj === null) {
            self::$obj = new self();
        }
        return self::$obj;
    }

    public function getCSSDir(): string
    {
        return \realpath($this->getRoot() . '/css');
    }

    public function getRoot(): string
    {
        return \realpath(\dirname(__DIR__, 4) . '/db/');
    }

    public function getJSDir(): string
    {
        return \realpath($this->getRoot() . '/js');
    }

    public function getFontDir(): string
    {
        return \realpath($this->getRoot() . '/fonts');
    }
}