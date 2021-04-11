<?php
//Збереження змін блоку сторінки
use zukr\log\Log;
use zukr\template\TemplateRepository;


$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$template = (new TemplateRepository())->findById($id);
if ($template === null) {
    Go_page('error');
}
$template->load($_POST);

$save = $template->save();

$log = Log::getInstance();
if ($template->id > 0) {
    $log->logAction(null, $template::getTableName(), $template->id);
}
if (isset($_POST['save'])) {
    $url2go = 'action.php?action=template_edit&id=' . $id;
}
if (isset($_POST['save+exit'])) {
    $url2go = 'action.php?action=template_list';
}
Go_page($url2go);
