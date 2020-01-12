<?php


namespace zukr\api\actions;

use zukr\author\AuthorRepository;
use zukr\base\exceptions\InvalidArgumentException;
use zukr\leader\LeaderRepository;
use zukr\log\Log;

/**
 * Class ChangeArrivalAction
 *
 * Зміна відмітки про прибуття
 *
 * @package      zukr\api\actions
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class ChangeArrivalAction implements ApiActionsInterface
{

    use ApiMessageTrait;
    /**
     * @var int ІД запису
     */
    private $id;
    /**
     * @var string
     */
    private $person;
    /**
     * @var int
     */
    private $value;

    /**
     * @inheritDoc
     */
    public function execute()
    {
        if (\in_array($this->person, ['author', 'leader'])) {
            if ($this->person === 'author') {
                $person = (new AuthorRepository())->findById($this->id);
            } else {
                $person = (new LeaderRepository())->findById($this->id);
            }
            if ($person !== null) {
                $person->arrival = $this->value;
                $save = $person->save();
                if ($save) {
                    $log = Log::getInstance();
                    $log->logAction(null, $person::getTableName(), $person->id);
                    $this->changeMessage();
                }
            }

        }
        return $this->getMessage();
    }

    /**
     * @param array $params
     * @throws InvalidArgumentException
     */
    public function init(array $params = [])
    {
        if (empty($this->id = \filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT))) {
            throw new InvalidArgumentException('id Must be set');
        }
        if (
            ($this->value = \filter_input(INPUT_POST, 'value', FILTER_VALIDATE_INT)) === null

        ) {
            throw new InvalidArgumentException('value Must be set');
        }

        if (empty($this->person = \filter_input(INPUT_POST, 'person', FILTER_SANITIZE_STRING))) {
            throw new InvalidArgumentException('person Must be set');

        }

    }
}