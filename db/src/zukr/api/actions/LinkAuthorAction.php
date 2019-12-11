<?php


namespace zukr\api\actions;

use zukr\log\Log;
use zukr\workauthor\WorkAuthor;
use zukr\workleader\WorkLeaderRepository;

/**
 * Class LinkAuthorAction
 *
 * @package      zukr\api\actions
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class LinkAuthorAction implements ApiActionsInterface
{

    /** @var int */
    public $id_a;
    /** @var */
    public $id_w;

    /**
     * @param array $params
     */
    public function init(array $params = [])
    {
        $this->id_a = filter_input(INPUT_POST, 'id_a', FILTER_VALIDATE_INT);
        $this->id_w = filter_input(INPUT_POST, 'id_w', FILTER_VALIDATE_INT);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $autorsOfWork = (new WorkLeaderRepository())->getCountAuthorsByWorkId($this->id_w);
        if ($autorsOfWork['count'] < N_AUTORS) {
            $wl = new WorkAuthor();
            $wl->load(['id_a' => $this->id_a, 'id_w' => $this->id_w], false);
            $wl->save();
            $log = Log::getInstance();
            $log->logAction('Link-Author-Action', 'wa', $wl->id);
        }
        return 'Test ' . $autorsOfWork['count'];
    }
}