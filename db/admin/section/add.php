<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 14:44
 */

//Добавление секции
use zukr\log\Log;
use zukr\section\Section;

$section = new Section();
$section->load($_POST);
$save = $section->save();
$log = Log::getInstance();
$log->logAction(null, $section::getTableName(), $section->id);
$url2go = 'action.php?action=section_list';
Go_page($url2go);