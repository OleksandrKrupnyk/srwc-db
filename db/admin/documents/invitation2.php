<?php

use zukr\base\Base;
use zukr\base\helpers\ArrayHelper;
use zukr\base\ReplacerService;
use zukr\pdf\PdfWrapper;
use zukr\template\TemplateNameDictionary;
use zukr\template\TemplateService;

$db = Base::$app->db;
$results = $db->rawQuery("
SELECT autors.id as autorNumber, 
     CONCAT(autors.suname,' ',autors.name,' ',autors.lname) as fio_a, 
     univers.univerrod as univer, 
     univers.id as id,
     autors.curse as curse
FROM autors
  LEFT JOIN univers ON univers.id=autors.id_u
  LEFT JOIN wa ON autors.id=wa.id_a
  LEFT JOIN works ON wa.id_w = works.id
  WHERE works.invitation = 1 AND univers.id <> '1'
  ORDER BY univer,fio_a
");
$univers = ArrayHelper::group($results, 'id');
$content = '';
$list = [];
foreach ($univers as $students) {
    $studentList = [];
    foreach ($students as &$student) {
        // Todo: Переробити в шаблон
        $studentList[] = vsprintf("<li>%s, (№%s)</li>", [$student['fio_a'], $student['autorNumber']]);
    }
    $list[] = [
        'univer' => $student['univer'],
        'studentList' => $studentList
    ];
    unset($student);
}
$template = (new TemplateService())
    ->getBlockByName(TemplateNameDictionary::INVITATION2);
$replaceService = (new ReplacerService());
$content = '';
foreach ($list as $page) {

    $content .= $replaceService->makeReplace($template, [
        '{@univer}' => $page['univer'],
        '{@studentlist}' => '<ol>' . implode('', $page['studentList']) . '</ol>',
    ]);
}
if (filter_input(INPUT_GET, 'pdf')) {
    $pdf = PdfWrapper::getInstance();
    $pdf->getPdf($content);
} else {
    echo $content;
}