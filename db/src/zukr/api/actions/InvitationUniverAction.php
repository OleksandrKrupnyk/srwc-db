<?php


namespace zukr\api\actions;


use zukr\base\exceptions\InvalidArgumentException;
use zukr\base\exceptions\NullReturnedException;
use zukr\log\Log;
use zukr\univer\UniverRepository;

/**
 * Class InvitationUniverAction
 *
 * Відмітка про перше інформаційне повідомлення для університетів
 *
 * @package      zukr\api\actions
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class InvitationUniverAction implements ApiActionsInterface
{

    use ApiMessageTrait;
    /**
     * @var int ІД університету
     */
    protected $id_u;
    /**
     * @var int Ознака запрошення університету
     */
    protected $invite;

    /**
     * @throws NullReturnedException
     */
    public function execute()
    {

        $univer = (new UniverRepository())->findById($this->id_u);
        if ($univer === null) {
            throw new NullReturnedException('$univer Return value is null');
        }

        $univer->invite = $this->invite;
        $save = $univer->save();
        if ($save) {
            $log = Log::getInstance();
            $log->logAction(null, $univer::getTableName(), $univer->id);
            $this->changeMessage();
        }
        return $this->getMessage();
    }

    /**
     * @param array $params
     * @throws InvalidArgumentException
     */
    public function init(array $params = [])
    {
        if (empty($this->id_u = \filter_input(INPUT_POST, 'id_u', FILTER_VALIDATE_INT))) {
            throw new InvalidArgumentException('id_u Must be set');
        }
        if (
            ($this->invite = \filter_input(INPUT_POST, 'invite', FILTER_VALIDATE_INT)) === null
        ) {
            throw new InvalidArgumentException('invite Must be set');
        }
    }
}