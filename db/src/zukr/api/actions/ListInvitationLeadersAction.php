<?php


namespace zukr\api\actions;


use zukr\base\exceptions\InvalidArgumentException;
use zukr\leader\LeaderHelper;

/**
 * Class ListInvitationLeadersAction
 *
 * Список керівніків робіт
 *
 * @package      zukr\api\actions
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class ListInvitationLeadersAction implements ApiActionsInterface
{

    /**
     * @var int ІД запису університету
     */
    private $id_u;

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $lh = LeaderHelper::getInstance();

        $leaders = $lh->getLeaderRepository()->getAllByUniverId($this->id_u);
        if (!empty($leaders)) {
            return $lh->listWithCheckBox($leaders);
        }
    }

    /**
     * @inheritDoc
     */
    public function init(array $params = [])
    {
        if (empty($this->id_u = \filter_input(INPUT_POST, 'id_u', FILTER_VALIDATE_INT))) {
            throw new InvalidArgumentException('id_u Must be set');
        }
    }
}