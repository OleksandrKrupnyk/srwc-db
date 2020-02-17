<?php


namespace zukr\api\actions;

use zukr\author\Author;
use zukr\author\AuthorRepository;
use zukr\base\exceptions\InvalidArgumentException;
use zukr\log\Log;
use zukr\workauthor\WorkAuthorRepository;

/**
 * Class DeleteAuthorAction
 *
 * Видалення запису автора роботи
 *
 * @package      zukr\api\actions
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class DeleteAuthorAction implements ApiActionsInterface
{
    /**
     * @var int ІД запису автора
     */
    public $id;

    /**
     * @inheritDoc
     */
    public function execute()
    {
        try {
            $workAuthorData = (new WorkAuthorRepository())->getByAuthorId($this->id);
            if (!empty($workAuthorData)) {
                throw new \Exception('Автор звязаний з роботою', 610);
            }
            $authorData = (new AuthorRepository())->getById($this->id);
            if (empty($authorData)) {
                throw new \Exception('Не можу отримати запис автора', 615);
            }
            $author = new Author();
            $authorQuery = $author->getDb();
            $authorQuery->startTransaction();
            $authorQuery->where('id', $this->id);

            $delete = $author->delete($authorQuery);
            $log = Log::getInstance();
            $log->logAction('Delete-Author-Action', 'authors', $this->id);
            ($delete) ? $authorQuery->commit() : $authorQuery->rollback();
            $response = ['msg' => 'Запис автора видалено', 'code' => 0];
        } catch (\Exception $e) {
            if (isset($authorQuery) && $authorQuery !== null) {
                $authorQuery->rollback();
            }
            $response = ['msg' => $e->getMessage(), 'code' => $e->getCode()];
        }
        echo \json_encode($response);

    }

    /**
     * @inheritDoc
     */
    public function init(array $params = [])
    {
        if (empty($this->id = \filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT))) {
            throw new InvalidArgumentException('id Must be set');
        }
    }
}