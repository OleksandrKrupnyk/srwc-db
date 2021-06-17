<?php

use zukr\base\Base;
use zukr\pdf\PdfWrapper;
use zukr\template\TemplateNameDictionary;
use zukr\template\TemplateService;

$db = Base::$app->db;
$leaders = $db->rawQuery("
SELECT leaders.id,
       univers.univerrod AS univer, 
       CONCAT(leaders.suname,' ',leaders.name,' ',leaders.lname) AS fio_l,
       position,
       status,
       degree 
FROM leaders  
  JOIN univers on leaders.id_u = univers.id  
  LEFT outer JOIN  positions ON leaders.id_pos = positions.id
  LEFT outer join statuses ON leaders.id_sat = statuses.id 
  LEFT outer join degrees ON leaders.id_deg = degrees.id 
WHERE ((leaders.arrival = '1') and (univers.id <> 1)) 
GROUP BY leaders.id_u,fio_l 
ORDER BY univer, fio_l
");
if (!empty($leaders)) {
    $content = '';
    $template = (new TemplateService())
        ->getBlockByName(TemplateNameDictionary::GRATITUDE);
    $replaceService = (new \zukr\base\ReplacerService());
//    $template = <<<__HTML__
//<div class="gratitudes">
//<div class="line1">нагороджується {@position}</div>
//<div class="line2">{@univer}</div>
//<div class="line3">{@leader} </div>
//<div class="line4">за активну участь в підготовці та проведенні підсумкової конференції</div>
//<div class="line5">&quot;Електротехніка та електромеханіка - {@year}&quot;</div>
//<div class="line6">Всеукраїнському конкурсі студентських наукових <br> робіт {@nyears} навчального року з галузі<br/>
//&quot;Електротехніка та електромеханіка&quot;</div>
//<div class="line8">Перший проректор ДДТУ,<br/>
//Голова галузевої конкурсної комісії,<br/> д.т.н., професор
//</div>
//<div class="line9"><br/><br/>В.М.Гуляєв</div>
//<div class="line10">м. Кам’янське {@year}</div>
//</div><hr>
//__HTML__;

    foreach ($leaders as $gratitude){
        $str = '';
        if ($gratitude['degree'] !== '-немає-') {
            $str .= $gratitude['degree'];
            if ($gratitude['status'] !== '-немає-') {
                $str .= ', ' . $gratitude['status'];
            }
        } else {
            $str .= $gratitude['position'];
        }
        $a = [
            '{@position}'=>$str,
            '{@univer}'=>$gratitude['univer'],
            '{@leader}'=>strtoupper($gratitude['fio_l'])
        ];
        $gratitude = $replaceService->makeReplace($template, $a);
        $content .= $gratitude;
    }
}else{
    $content = '<mark>За даним запитом даних не знайдено!</mark>';
}
if (filter_input(INPUT_GET, 'pdf')) {
    $pdf = PdfWrapper::getInstance();
    $pdf->getPdf($content);
} else {
    echo $content;
}
