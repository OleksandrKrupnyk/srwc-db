<?php


namespace zukr\workauthor;

/**
 * Class WorkAuthorHelper
 *
 * @package      zukr\workauthor
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class WorkAuthorHelper
{
    /** @var WorkAuthorHelper */
    private static $obj;

    /** @var array */
    private $worksAuthor = [];

    /**
     * WorkHelper constructor.
     */
    private function __construct()
    {

    }

    /**
     * @return WorkAuthorHelper
     */
    public static function getInstance(): WorkAuthorHelper
    {
        if (static::$obj === null) {
            static::$obj = new self();
        }
        return static::$obj;

    }

    /**
     * @param int $workId
     * @return array|\MysqliDb
     */
    public function getIdsAuthorsOfWorkByWorkId(int $workId)
    {
        $authors = (new WorkAuthorRepository())->getAllAuthorsOfWorkByWorkId($workId);
        return \array_map(static function ($v) {
            return $v['id'];
        }, $authors);
    }

    /**
     * @return array|\MysqliDb
     */
    public function getWorksAuthor()
    {
        if ($this->worksAuthor == []) {
            $this->worksAuthor = (new WorkAuthorRepository())->getAllAuthorsOfWorks();
        }
        return $this->worksAuthor;
    }


}