<?php
global $link;

use zukr\base\helpers\PersonHelper;

$listLeaders = (new \zukr\leader\LeaderRepository())->getListLeadersForHostel();
$listUniver = \zukr\base\helpers\ArrayHelper::group($listLeaders, 'univerrod');
if (!empty($listUniver)) {
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
    echo implode('', $txt);
} else {
    echo '<mark>За данним запитом данних не знайдено!</mark>';
}
