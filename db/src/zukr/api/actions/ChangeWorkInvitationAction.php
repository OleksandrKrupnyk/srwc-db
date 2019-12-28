<?php


namespace zukr\api\actions;


use zukr\base\exceptions\InvalidArgumentException;
use zukr\base\exceptions\NullReturnedException;
use zukr\log\Log;
use zukr\work\WorkRepository;

/**
 * Class ChangeWorkInvitationAction
 *
 * @package      zukr\api\actions
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class ChangeWorkInvitationAction implements ApiActionsInterface
{

    /**
     * @var int ІД Роботи
     */
    protected $id_w;
    /**
     * @var int Ознака запрошення роботи до участі у конференції
     */
    protected $invitation;

    /**
     * @throws NullReturnedException
     */
    public function execute()
    {
        $message = 'Значення не змінено';
        $type = 'error';
        $work = (new WorkRepository())->findById($this->id_w);
        if ($work === null) {
            throw new NullReturnedException('$univer Return value is null');
        }

        $work->invitation = $this->invitation;
        $save = $work->save();
        if ($save) {
            $log = Log::getInstance();
            $log->logAction(null,$work::getTableName(),$work->id);
            $message = 'Значення змінено';
            $type = 'success';
        }
        return \json_encode(\compact('message', 'type'));
    }

    /**
     * @param array $params
     * @throws InvalidArgumentException
     */
    public function init(array $params = [])
    {
        if (empty($this->id_w = \filter_input(INPUT_POST, 'id_w', FILTER_VALIDATE_INT))) {
            throw new InvalidArgumentException('id_w Must be set');
        }
        if (
            ($this->invitation = \filter_input(INPUT_POST, 'invitation', FILTER_VALIDATE_INT)) === null
        ) {
            throw new InvalidArgumentException('invitation Must be set');
        }
    }
}