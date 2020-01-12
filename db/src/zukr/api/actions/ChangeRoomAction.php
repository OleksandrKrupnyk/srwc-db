<?php


namespace zukr\api\actions;

use zukr\base\exceptions\InvalidArgumentException;
use zukr\base\exceptions\NullReturnedException;
use zukr\log\Log;
use zukr\section\SectionRepository;

/**
 * Class ChangeRoomAction
 *
 * Зміна адиторії засідання секції
 *
 * @package      zukr\api\actions
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class ChangeRoomAction implements ApiActionsInterface
{
    use ApiMessageTrait;
    /**
     * @var int ІД секції
     */
    protected $id_sec;
    /**
     * @var string
     */
    protected $room;

    /**
     * @inheritDoc
     * @throws NullReturnedException
     */
    public function execute()
    {

        $section = (new SectionRepository())->findById($this->id_sec);
        if ($section === null) {
            throw new NullReturnedException('$section Return value is null');
        }
        $section->room = $this->room;
        $save = $section->save();
        if ($save) {
            $log = Log::getInstance();
            $log->logAction(null, $section::getTableName(), $section->id);
            $this->changeMessage();
        }
        return $this->getMessage();
    }

    /**
     * @inheritDoc
     * @throws InvalidArgumentException
     */
    public function init(array $params = [])
    {
        if (empty($this->id_sec = \filter_input(INPUT_POST, 'id_sec', FILTER_VALIDATE_INT))) {
            throw new InvalidArgumentException('id_sec Must be set');

        };
        if (empty($this->room = \filter_input(INPUT_POST, 'room', FILTER_SANITIZE_STRING))) {
            throw new InvalidArgumentException('room Must be set');

        }
    }
}