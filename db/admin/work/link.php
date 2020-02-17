<?php

use zukr\log\Log;
use zukr\workauthor\WorkAuthor;
use zukr\workleader\WorkLeader;

if (($id_w = filter_input(INPUT_POST, 'id_w', FILTER_VALIDATE_INT)) !== false) {
    $log = Log::getInstance();
    if (!empty($leaders = filter_input(INPUT_POST, 'leaders', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY))) {
        foreach ($leaders as $id_l) {
            if ((int)$id_l === -1) {
                continue;
            }
            /** @var WorkLeader $workLeader */
            $workLeader = new WorkLeader();
            $workLeader->id_w = $id_w;
            $workLeader->id_l = $id_l;
            $workLeader->save();
            $log->logAction(null, $workLeader::getTableName(), $workLeader->id ?? 0);
        }
    }
    if (!empty($authors = filter_input(INPUT_POST, 'authors', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY))) {
        foreach ($authors as $id_a) {
            if ((int)$id_a === -1) {
                continue;
            }
            /** @var  WorkAuthor $workAuthor */
            $workAuthor = new WorkAuthor();
            $workAuthor->id_w = $id_w;
            $workAuthor->id_a = $id_a;
            $workAuthor->save();
            $log->logAction(null, $workAuthor::getTableName(), $workAuthor->id ?? 0);
        }
    }
    Go_page('action.php?action=all_view#id_w' . $id_w);
} else {
    Go_page('action.php');
}