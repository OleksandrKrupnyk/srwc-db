<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 29.03.2018
 * Time: 20:15
 */

$countReview = array_sum($_GET['a']);
$c = [];
foreach ($_GET['a'] as $count) {
    //echo $count."<br>";
    $c[] = (int)($count * 360 / $countReview);
}
// создание изображения
$image = imagecreatetruecolor(460, 500);
$white = imagecolorallocate($image, 255, 255, 255);

imagefilledrectangle($image, 0, 0, 460, 660, $white);
// определение цветов
$red = imagecolorallocate($image, 255, 0, 0);
$pink = imagecolorallocate($image, 255, 96, 208);
$purpure = imagecolorallocate($image, 160, 32, 255);
$lblue = imagecolorallocate($image, 80, 208, 255);
$blue = imagecolorallocate($image, 80, 32, 255);
$ygreen = imagecolorallocate($image, 96, 255, 128);
$green = imagecolorallocate($image, 0, 192, 0);
$yellow = imagecolorallocate($image, 255, 224, 32);
$gyellow = imagecolorallocate($image, 173, 255, 47);
$black = imagecolorallocate($image, 0, 0, 0);

$color = [$red, $pink, $purpure, $lblue, $blue, $ygreen, $green, $yellow, $gyellow];


// делаем эффект 3Д
$angles = [];
$angles[0] = 0;
$sum1 = 0;

for ($i = 1; $i < count($c); $i++) {
    $sum1 += $c[$i - 1];
    $angles[$i] = $sum1;
}

$angles[count($c)] = 360;
//print_r($angles);

for ($i = 0; $i < count($angles) - 1; $i++) {
    imagefilledarc($image, 230, 230, 420, 420, $angles[$i] - 90, $angles[$i + 1] - 90, $color[$i], IMG_ARC_PIE);
    //echo $i.".|[".($angles[$i])."; ". ($angles[$i+1])."]".($color[$i])."<br>";
}

for ($i = 1; $i < count($c) + 1; $i++) {

    imagefilledrectangle($image, 10 + $i * 30, 450, 35 + $i * 30, 480, $color[$i - 1]);
    imagestring($image, 12, 20 + $i * 30, 460, "{$i}.", $black);
    imagestring($image, 12, 19 + $i * 30, 458, "{$i}.", $white);
}
// вывод изображения
header('Content-type: image/png');
imagepng($image);
imagedestroy($image);