<?php


namespace zukr\api\actions;

use zukr\log\Log;
use zukr\workauthor\WorkAuthor;

/**
 * Class UnlinkAuthorAction
 *
 * @package      zukr\api\actions
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class UnlinkAuthorAction implements ApiActionsInterface
{


    /**
     * @var int
     */
    public $id_a;
    /**
     * @var
     */
    public $id_w;

    /**
     * @param array $params
     */
    public function init(array $params = []): void
    {
        $this->id_a = \filter_input(INPUT_POST, 'id_a', FILTER_VALIDATE_INT);
        $this->id_w = \filter_input(INPUT_POST, 'id_w', FILTER_VALIDATE_INT);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function execute()
    {
        $workAuthor = new WorkAuthor();
        $queryAuthor = $workAuthor->getDb();
        $queryAuthor
            ->where('id_w', $this->id_w)
            ->where('id_a', $this->id_a);
        $delete = $workAuthor->delete($queryAuthor);
        $log = Log::getInstance();
        $log->logAction('Unlink-Author-Action', 'wa', '0');
        return 'Test ' . $autorsOfWork['count'];
    }
}