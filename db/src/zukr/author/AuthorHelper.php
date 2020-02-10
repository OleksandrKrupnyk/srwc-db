<?php


namespace zukr\author;


use zukr\base\Base;
use zukr\base\helpers\ArrayHelper;
use zukr\base\RecordHelper;
use zukr\workauthor\WorkAuthor;
use zukr\workauthor\WorkAuthorRepository;

/**
 * Class AuthorHelper
 *
 * @package      zukr\author
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class AuthorHelper extends RecordHelper
{
    /** @var AuthorHelper */
    private static $obj;

    /** @var array */
    private $worksAutors;
    /** @var array */
    private $autorsOfWork;
    /** @var */
    private $autors;

    /**
     * @var WorkAuthorRepository
     */
    private $workAuthorRepository;

    /**
     * @return AuthorHelper
     */
    public static function getInstance(): AuthorHelper
    {
        if (static::$obj === null) {
            static::$obj = new static();
        }
        return static::$obj;
    }

    /**
     * @param $workId
     * @return array|mixed
     */
    public function getAutorsByWorkId($workId)
    {
        if ($this->autorsOfWork === null) {
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

            $worksAutors = Base::$app->cacheGetOrSet(
                WorkAuthor::class,
                function () {
                    return $this->getWorkAuthorRepository()->getAllAuthorsOfWorks();
                }, 360);
            $this->worksAutors = $worksAutors;
        }
        return $this->worksAutors;
    }

    /**
     * @return array|null
     */
    protected function getAutors(): ?array
    {
        if ($this->autors === null) {
            $autors = (new AuthorRepository())->getAllAuthors();
            $this->autors = $autors;
        }
        return $this->autors;
    }

    /**
     * @param int $univerId
     * @return array
     */
    public function getAllAuthorsByUniverId(int $univerId): array
    {
        $authors = $this->getAutors();
        if (!empty($authors)) {
            return \array_filter($this->getAutors(), static function ($author) use ($univerId) {
                return $author['id_u'] === $univerId;
            });
        }
        return [];
    }

    /**
     * @return WorkAuthorRepository
     */
    public function getWorkAuthorRepository(): WorkAuthorRepository
    {
        if ($this->workAuthorRepository === null) {
            $this->workAuthorRepository = new WorkAuthorRepository();
        }
        return $this->workAuthorRepository;
    }

}