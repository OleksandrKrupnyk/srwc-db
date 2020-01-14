<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 15:02
 */

use zukr\base\Base;
use zukr\log\Log;
use zukr\univer\UniverHelper;

$id_u = filter_input(INPUT_POST, 'id_u', FILTER_VALIDATE_INT);
$uh = UniverHelper::getInstance();
$univer = $uh->getUniverRepository()->findById($id_u);
if ($univer === null || !$id_u) {
    Go_page('error');
}
$univer->load($_POST);
$univer->save();
$log = Log::getInstance();
$log->logAction(null, $univer::getTableName(), $univer->id);
if (isset($_POST['save'])) {
    $url2go = 'action.php?' . http_build_query(['action' => 'univer_edit', 'id_u' => $univer->id]);
} elseif (isset($_POST['save+exit'])) {
    $url2go = $url2go = Base::$session->get('redirect_to', 'action.php?' . http_build_query(['action' => 'all_view']));
}
Go_page($url2go);