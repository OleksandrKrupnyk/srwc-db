<?php

use zukr\base\helpers\ArrayHelper;
use zukr\base\helpers\PersonHelper;
use zukr\leader\LeaderRepository;

$listLeaders = (new LeaderRepository())->getListLeadersForHostel();
$html = '';
if (!empty($listLeaders)) {
    $listUniver = ArrayHelper::group($listLeaders, 'univerrod');
    $txt = [];
    $txt[] = '<h1>Список керівників на поселеня</h1>';
    foreach ($listUniver as $univer => $listLeaders) {
        $txt[] = '<div id="univer_title"><em>' . $univer . '</em></div>';
        $list = [];
        foreach ($listLeaders as $leader) {
            $list [] = '<li>' . PersonHelper::getFullName($leader) . '</li>';
        }
        $txt[] = '<ol>' . implode('', $list) . '</ol>';
    }
    $html = implode('', $txt);
} else {
    $html = '<mark>За данним запитом данних не знайдено!</mark>';
}
echo $html;