<?php
//Дипломы
use zukr\base\Base;
use zukr\base\helpers\PersonHelper;
use zukr\pdf\PdfWrapper;
use zukr\template\TemplateNameDictionary;
use zukr\template\TemplateService;

$ph = \zukr\place\PlaceHelper::getInstance();
$db = Base::$app->db;
$diploms = $db->rawQuery("
SELECT
  autors.place,
  univers.univerrod,
  sections.section,
  works.title,
  autors.suname AS F,
  autors.name AS I,
  autors.lname AS O
FROM autors
  JOIN univers ON univers.id = autors.id_u
  JOIN wa ON wa.id_a = autors.id
  JOIN works ON wa.id_w = works.id
  JOIN sections ON works.id_sec = sections.id
 WHERE autors.place <> 'D' AND autors.arrival = '1'
ORDER BY univers.univerrod;");
if (!empty($diploms)) {
    $content ='';
    $template = (new TemplateService())
        ->getBlockByName(TemplateNameDictionary::DIPLOM);
    $replaceService = (new \zukr\base\ReplacerService());
//    $template = <<<__HTML__
//<div class="diplom">
//<div class="line1">{@place_world}</div>
//<div class="line2">НАГОРОДЖУЄТЬСЯ:</div>
//<div class="line3">{@student_person} {@univer}</div>
//<div class="line4">{@student_fio}</div>
//<div class="line5">за наукову роботу:<br/>
//&quot;{@work}&quot;</div>
//<div class="line6"> у Всеукраїнському конкурсі студентських наукових <br/>
//робіт {@nyears} навчального року з галузі знань<br/>
//&quot;Електротехніка та електромеханіка&quot;</div>
//<div class="line7">Секція &quot;{@section}&quot;</div>
//<div class="line8">Перший проректор ДДТУ,<br/>
//Голова галузевої конкурсної комісії<br/>
//д.т.н., професор<br/>
//</div>
//<div class="line9"><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/>
//В.М.Гуляєв</div>
//<div class="line10">м. Кам’янське {@year}</div>
//</div>
//__HTML__;
    foreach ($diploms as $diplom) {
        $a = [
            '{@place_world}' => $ph->diplomString($diplom['place']),
            '{@student_person}' => PersonHelper::student_ka($diplom['O']),
            '{@univer}' => $diplom['univerrod'],
            '{@student_fio}' => $diplom['F'] . ' ' . $diplom['I'] . ' ' . $diplom['O'],
            '{@work}' => $diplom['title'],
            '{@section}' => $diplom['section'],
        ];
        $charters = $replaceService->makeReplace($template, $a);
        $content .= $charters;
    }
}else{
    $content =  '<mark>За даним запитом даних не знайдено!</mark>';
}
if (filter_input(INPUT_GET, 'pdf')) {
    $pdf = PdfWrapper::getInstance();
    $pdf->getPdf($content);
} else {
    echo $content;
}