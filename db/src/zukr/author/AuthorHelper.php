<?php


namespace zukr\author;


use zukr\base\helpers\ArrayHelper;
use zukr\workauthor\WorkAuthorRepository;

/**
 * Class AuthorHelper
 *
 * @package      zukr\author
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class AuthorHelper
{
    /** @var AuthorHelper */
    private static $obj;

    /** @var array */
    private $worksAutors;
    /** @var array */
    private $autorsOfWork;


    /**
     * WorkHelper constructor.
     */
    private function __construct()
    {

    }

    /**
     * @return AuthorHelper
     */
    public static function getInstance(): AuthorHelper
    {
        if (static::$obj === null) {
            static::$obj = new self();
        }
        return static::$obj;

    }

    /**
     * @param $workId
     * @return array|mixed
     */
    public function getAutorsByWorkId($workId)
    {
        if ($this->autorsOfWork == null) {
            $worksAutors = $this->getWorksAutors();
            $this->autorsOfWork = ArrayHelper::group($worksAutors, 'id_w');
        }
        return $this->autorsOfWork[$workId] ?? [];
    }

    /**
     * @return array|\MysqliDb
     */
    protected function getWorksAutors()
    {
        if ($this->worksAutors === null) {
            $worksAutors = (new WorkAuthorRepository())->getAllAuthorsOfWorks();
            $this->worksAutors = $worksAutors;
        }
        return $worksAutors;
    }


}