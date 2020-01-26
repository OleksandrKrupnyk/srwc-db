<?php

use zukr\author\AuthorRepository;
use zukr\base\Base;
use zukr\base\helpers\ArrayHelper;
use zukr\base\helpers\PersonHelper;

$db = Base::$app->db;
$html = '';
$listAuthors = (new AuthorRepository())->getListAutorsForHostel();
if (!empty($listAuthors)) {
    $listUnivers = ArrayHelper::group($listAuthors, 'univer');
    unset($listAuthors);
    $html = '<h1>Список студентів на поселеня у гуртожитку</h1>';
    foreach ($listUnivers as $univer => $autors) {
        $html .= '<div id="univer_title"><em>' . $univer . '</em></div>';
        $list = [];
        foreach ($autors as $a) {
            $list[] = '<li>' . PersonHelper::getFullName($a) . '</li>';
        }
        $html .= '<ol>' . implode('', $list) . '</ol>';
    }
} else {
    $html = '<mark>За данним запитом данних не знайдено!</mark>';
}
echo $html;
