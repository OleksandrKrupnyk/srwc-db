<?php


namespace zukr\author;

/**
 * Class AuthorRepository
 *
 * @package      zukr\author
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class AuthorRepository
{
    /**
     * @var Author
     */
    private static $author;

    /**
     * @return mixed
     */
    private static function getAuthor()
    {

        if (static::$author === null) {
            static::$author = new Author();
        }
        return static::$author;
    }

    /**
     * @param $id
     * @return Author|null
     */
    public static function findById($id)
    {
        $authorData = self::getById($id);
        if (!empty($authorData)) {
            static::$author->load($authorData, false);
            return static::$author;
        }
        return null;
    }

    /**
     * @param $id
     * @return array
     */
    public static function getById($id): array
    {
        $author = static::getAuthor();
        return $author !== null ? $author->findById($id) : [];
    }

}