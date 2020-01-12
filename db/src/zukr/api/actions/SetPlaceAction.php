<?php


namespace zukr\api\actions;

use zukr\author\Author;
use zukr\author\AuthorRepository;
use zukr\base\exceptions\InvalidArgumentException;
use zukr\log\Log;

/**
 * Class SetPlaceAction
 *
 * Встановлення місця
 *
 * @package      zukr\api\actions
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class SetPlaceAction implements ApiActionsInterface
{

    use ApiMessageTrait;

    /**
     * @var string Місце
     */
    private $place;
    /**
     * @var int ІД запису автора
     */
    private $id_a;

    /**
     * @inheritDoc
     */
    public function execute()
    {
        if (\in_array($this->place, Author::PLACES)) {
            /**
             * @var Author $author
             */
            $author = (new AuthorRepository())->findById($this->id_a);
            if ($author !== null) {
                $author->place = $this->place;
                $save = $author->save();
                if ($save) {
                    $log = Log::getInstance();
                    $log->logAction(null, $author::getTableName(), $author->id);
                    $this->changeMessage();
                }
            }
            return $this->getMessage();
        }
    }

    /**
     * @inheritDoc
     */
    public function init(array $params = [])
    {
        if (empty($this->place = \filter_input(INPUT_POST, 'place', FILTER_SANITIZE_STRING))) {
            throw new InvalidArgumentException('place Must be set');
        };

        if (empty($this->id_a = \filter_input(INPUT_POST, 'id_a', FILTER_VALIDATE_INT))) {
            throw new InvalidArgumentException('id_a Must be set');

        };


    }
}