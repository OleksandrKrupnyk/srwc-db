<?php
require 'config.inc.php';
require 'functions.php';
header('Content-Type: text/html; charset=utf-8');
global $link;
global $settings;
require_once 'Mail.php';
require_once 'Mail/mime.php';
read_settings();
// Настройки почты
$params['host'] = 'ssl://smtp.gmail.com';
$params['port'] = '465';
$params['auth'] = true;
$params['username'] = 'user@gmail.com';
$params['password'] = 'password';
$params['debug'] = 'False';

//Параметры міме
$mimeparams = [];
$mimeparams['html_charset'] = 'UTF-8';
$mimeparams['head_charset'] = 'UTF-8';

//От кого и тема письма
$headers['From'] = '"Конкурс СНР" <conkstudrabot@gmail.com>';
$headers['Subject'] = 'Запрошення на участь у конференції';
//$headers["Subject"] = "ТЕСТОВИЙ ЛИСТ СИСТЕМИ2";

$get_text = '&t=' . $_POST['t'];
$crlf = "\n";
$mime = new Mail_mime($crlf);
//print_r($_POST);
//Определяем с какой таблицей работаем
if ($_POST['t'] === "a") { // авторы работи
    $html_base = $_POST['letter2autors'];
    //Запишем изменения в файле шаблона
    file_put_contents('letter2autors.tte', $html_base);
} elseif ($_POST['t'] === "l") {//руководители
    $html_base = $_POST['letter2leaders'];
    //Запишем изменения в файле шаблона
    file_put_contents('letter2leaders.tte', $html_base);

}
//Если все полученные данные являются массивами то выполнять
if (
    filter_input(INPUT_POST, 'emails', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) &&
    filter_input(INPUT_POST, 'whom', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) &&
    filter_input(INPUT_POST, 'hashs', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) &&
    filter_input(INPUT_POST, 'titles', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY)
) {
    $emails = filter_input(INPUT_POST, 'emails', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $whom = filter_input(INPUT_POST, 'whom', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $hashs = filter_input(INPUT_POST, 'hashs', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $titles = filter_input(INPUT_POST, 'titles', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $i = 0;

    foreach ($emails as $key => $email) {
        /* $email - почта */
        $headers['To'] = "\"{$whom[$key]}\" <{$email}>";
        $recipient     = "<{$email}>";
        //Замена
        $html = str_replace('{whom}', "{$whom[$key]}", $html_base);
        $html = str_replace("{title}", "{$titles[$key]}", $html);
        $html = str_replace("{link}", "http://elm-dstu-edu.org.ua/db/admin/getmails.php?hash=" . $hashs[$key] . $get_text, $html);
        $html = str_replace("{email}", "{$emails[$key]}", $html);
        //Создание тела
        $mime->setHTMLBody($html);
        //print_r(quoted_printable_decode($headers["To"]));
        $message = $mime->get($mimeparams);
        $headers2 = $mime->headers($headers);

        if ('1' == $settings['ALLOW_EMAIL']) {
            $mail_message =& Mail::factory('smtp', $params);
            $mail_message->send($recipient, $headers2, $message);
            unset($headers2);
        } else echo "<pre>" . $html . "</pre><hr>";
    }
}