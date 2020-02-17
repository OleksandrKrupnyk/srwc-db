<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 15:06
 */

use zukr\log\Log;
use zukr\workauthor\WorkAuthor;
use zukr\workleader\WorkLeader;


if (($id_w = filter_input(INPUT_GET, 'id_w', FILTER_VALIDATE_INT)) !== false) {
    $log = Log::getInstance();
    if (($id_a = filter_input(INPUT_GET, 'id_a', FILTER_VALIDATE_INT)) !== false) {
        $workAuthor = new WorkAuthor();
        $queryAuthor = $workAuthor->getDb();
        $queryAuthor->where('id_w', $id_w)
            ->where('id_a', $id_a);
        $delete = $workAuthor->delete($queryAuthor);
        if ($delete) {
            $log->logAction('delete_work_author', $workAuthor::getTableName(), $id_w);
        }
    }
    if (($id_l = filter_input(INPUT_GET, 'id_l', FILTER_VALIDATE_INT)) !== false) {
        $workLeader = new WorkLeader();
        $queryLeader = $workLeader->getDb();
        $queryLeader->where('id_w', $id_w)
            ->where('id_l', $id_l);
        $delete = $workLeader->delete($queryLeader);
        if ($delete) {
            $log->logAction('delete_work_author', $workLeader::getTableName(), $id_w);
        }
    }
}
Go_page('action.php?action=all_view#id_w' . $id_w);
