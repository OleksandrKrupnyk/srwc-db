<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 29.03.2018
 * Time: 20:15
 */

use function imagecolorallocate as rgb;

$countReview = array_sum($_GET['a']);
$c = [];
foreach ($_GET['a'] as $count) {
    $c[] = round($count * 360 / $countReview);
}
$countSectors = count($c);
// создание изображения
$image = imagecreatetruecolor(460, 500);
$white = imagecolorallocate($image, 255, 255, 255);

imagefilledrectangle($image, 0, 0, 460, 660, $white);
// определение цветов
$color = [
    rgb($image, 193, 0, 32),
    rgb($image, 255, 104, 0),
    rgb($image, 0, 131, 110),
    rgb($image, 80, 32, 255),
    rgb($image, 96, 255, 128),
    rgb($image, 31, 174, 233),
    rgb($image, 0, 192, 0),
    rgb($image, 255, 224, 32),
    rgb($image, 153, 17, 153),
    rgb($image, 102, 255, 0),
];

$sum1 = 0;
$angles [] = 0;
foreach ($c as $countDegree) {
    $sum1 += $countDegree;
    $angles[] = $sum1;
}
$angles[count($c)] = 360;

$countAngles = count($angles);
for ($i = 0; $i < $countAngles - 1; $i++) {
    imagefilledarc($image, 230, 230, 420, 420, $angles[$i] - 90, $angles[$i + 1] - 90, $color[$i], IMG_ARC_PIE);
    //    echo $i.".|[".($angles[$i])."; ". ($angles[$i+1])."]".($color[$i])."<br>";
}

for ($i = 1; $i <= $countSectors; $i++) {
    imagefilledrectangle($image, 10 + $i * 30, 450, 35 + $i * 30, 480, $color[$i - 1]);
    imagestring($image, 12, 20 + $i * 30, 460, "{$i}.", rgb($image, 0, 0, 0));
    imagestring($image, 12, 19 + $i * 30, 458, "{$i}.", rgb($image, 255, 255, 255));
}
// вывод изображения
header('Content-type: image/png');
imagepng($image);
imagedestroy($image);