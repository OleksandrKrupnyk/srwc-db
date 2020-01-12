<?php


namespace zukr\api\actions;

use zukr\base\exceptions\InvalidArgumentException;
use zukr\leader\Leader;
use zukr\leader\LeaderRepository;
use zukr\log\Log;

/**
 * Class InvitationLeaderAction
 *
 * Відмітка про запрошення автора
 *
 * @package      zukr\api\actions
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class InvitationLeaderAction implements ApiActionsInterface
{

    use ApiMessageTrait;
    /**
     * @var int ІД запис керівника
     */
    private $id_l;
    /**
     * @var int Значення запрошення
     */
    private $invitation;

    /**
     * @inheritDoc
     */
    public function execute()
    {
        /**
         * @var Leader
         */
        $leader = (new LeaderRepository())->findById($this->id_l);
        if ($leader !== null) {
            $leader->invitation = $this->invitation;
            $save = $leader->save();
            if ($save) {
                $log = Log::getInstance();
                $log->logAction(null, $leader::getTableName(), $leader->id);
                $this->changeMessage();
            }
        }
        return $this->getMessage();
    }

    /**
     * @inheritDoc
     */
    public function init(array $params = [])
    {
        if (empty($this->id_l = \filter_input(INPUT_POST, 'id_l', FILTER_VALIDATE_INT))) {
            throw new InvalidArgumentException('id_l Must be set');
        }
        if (
            ($this->invitation = \filter_input(INPUT_POST, 'invitation', FILTER_VALIDATE_INT)) === null
        ) {
            throw new InvalidArgumentException('invitation Must be set');
        }
    }
}