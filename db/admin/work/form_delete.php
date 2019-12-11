<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 15:08
 */

// удаление работы
use zukr\base\Base;
use zukr\base\exceptions\UnauthorizedAccessException;
use zukr\log\Log;
use zukr\user\UserRepository;
use zukr\work\Work;
use zukr\workauthor\WorkAuthor;
use zukr\workleader\WorkLeader;

try {
    $log = Log::getInstance();

    $admins = (new UserRepository())->getUserIdAsAdmin();
    $id_w = filter_input(INPUT_GET, 'id_w', FILTER_VALIDATE_INT);
    $session = Base::$session;
    $userId = (int)$session->get('id');

    if (in_array($userId, $admins, true) && $id_w) {

        $workLeader = new WorkLeader();
        $queryLeader = $workLeader->getDb();
        $queryLeader->startTransaction();
        $queryLeader->where('id_w', $id_w);
        $delete = $workLeader->delete($queryLeader);

        if ($delete) {
            $log->logAction(null, $workLeader::getTableName(), $id_w);
            $workAuthor = new WorkAuthor();
            $queryAuthor = $workAuthor->getDb();
            $queryAuthor->where('id_w', $id_w);
            $delete = $workAuthor->delete($queryAuthor);
            if ($delete) {
                $log->logAction(null, $workAuthor::getTableName(), $id_w);
                $work = new Work();
                $queryWork = $work->getDb();
                $queryWork->where('id', $id_w);
                $delete = $work->delete($queryWork);
                if ($delete) {
                    $log->logAction(null, $work::getTableName(), $id_w);
                    Base::$app->cacheFlush();
                }
            }
        }
        ($delete) ? $queryLeader->commit() : $queryLeader->rollback();
        $url2go = 'action.php?action=all_view';
    } else {
        throw new UnauthorizedAccessException(__CLASS__ . '::' . __METHOD__ . 'User with id: ' . $userId . ' try to make delete action');
    }
} catch (\Exception $e) {
    if ($queryLeader !== null) {
        $queryLeader->rollback();
    }
    $url2go = 'error';
    Base::$log->error($e->getMessage());
} finally {
    Go_page($url2go);
}