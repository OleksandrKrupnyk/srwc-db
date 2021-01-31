<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 15:03
 */

// Загрузка файла для работы

use Ramsey\Uuid\Uuid;
use zukr\base\Base;
use zukr\file\File;
use zukr\log\Log;


$file = new File();
$log = Log::getInstance();
if (isset($_FILES['file']))//проверяем загрузился ли файл
{
    //print_r($_FILES);
    $file_temp_name = $_FILES['file']['tmp_name'];
    $file_name = $_FILES['file']['name'];
    $file_size = $_FILES['file']['size'];
    $file_type = $_FILES['file']['type'];
    $error_code = $_FILES['file']['error'];
    $id_w = $_POST['id_w'];
    $typeoffile = (string)$_POST['typeoffile'];
    switch ($typeoffile) {
        case 'work':
            {
                $typeoffile = File::TYPE_WORK;
            }
            break;
        case 'tesis':
            {
                $typeoffile = File::TYPE_TESIS;
            }
            break;
        case "presentation":
            {
                $typeoffile = File::TYPE_OFFICE_PRESENTATION;
            }
            break;
        case "information":
            {
                $typeoffile = File::TYPE_INFORMATION;
            }
            break;

        default:
        {
            $typeoffile = File::TYPE_WORK;
        }
    };
    if ($error_code == 0)//Нет ли ошибок загрузки
    {
        //проверим есть ли уже каталог для материалов работы
        //если нет то создадим его
        if (!file_exists(DIR . $id_w)) {
            if (!mkdir($concurrentDirectory = DIR . $id_w . DIRECTORY_SEPARATOR, 0777, true)
                && !is_dir($concurrentDirectory)
            ) {
                Base::$log->error('Помилка при створенні теки для матеріалів роботи...');
                die('Помилка при створенні теки для матеріалів роботи...');
            }
        }
        //если он есть то удостоверимся что это действительно каталог
        if (\is_dir(DIR . $id_w)) {
            if (!\file_exists(DIR . $id_w . DIRECTORY_SEPARATOR . 'index.php')) {
                \file_put_contents(
                    DIR . $id_w . DIRECTORY_SEPARATOR . 'index.php',
                    "<?php\nheader(\"HTTP/1.0 404 Not Found\");"
                );
            }


            //да это каталог
            //Сформируем путь для копирования файла
            $file_name = DIR . $id_w . DIRECTORY_SEPARATOR . $file_name;
            // Если операционная система сервера windows то провести преобразование имени файла
            $fileNameCyrillic = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'
                ? iconv('UTF-8', 'windows-1251', $file_name)
                : $file_name;  // Если же Linux то ничего не делать

            if (!move_uploaded_file($file_temp_name, $fileNameCyrillic)) {
                echo '<pre>Помилка при копіюванні файлу</pre>';
                Base::$log->error('Помилка при копіюванні файлу...');
            } else {
                if (File::TYPE_WORK === $typeoffile) { // Если файл с текcтом работы то положим его в архив zip
                    if (!exec("zip -j " . DIR . $id_w . "/id_" . $id_w . "_text.zip  \"" . $fileNameCyrillic . "\"")) {
                        echo "<pre>Помилка при архівуванні файлу</pre>";
                        Base::$log->error('Помилка при архівуванні файлу');
                    } else { //удаление файла после архивирования
                        unlink($fileNameCyrillic);
                    }
                    //Новое имя для внесения в запись БД
                    $file_name = DIR . $id_w . "/id_" . $id_w . "_text.zip";
                }
                $file->file = $file_name;
                $file->typeoffile = $typeoffile;
                $file->mime_type = mime_content_type($file_name);
                $file->guid = Uuid::uuid4()->toString();
                $file->id_w = $id_w;
                $file->save();
                $log->logAction(null, $file::getTableName(), $file->id_w);
                Go_page("action.php?action=all_view#id_w" . $file->id_w);
                /*
                $query = "";//Очищаем запрос

                switch ($_POST['typeoffile'])//Проверяем тип загруженого файла и формируем запрос в БД для обновления
                {
                    case "tesis":
                        {
                            $query = "UPDATE `works` SET `tesis`='1',`date`=NOW() WHERE `id`='{$id_w}'";
                            $temptable = "works"; // табл.для лога
                        }
                        break;
                    case "presentation":
                        {
                            $query = "UPDATE `files` SET `typeoffile`='2',`date`=NOW() WHERE `id`='{$id_w}'";
                            $temptable = "files";
                        }
                        break;
                    case "information":
                        {
                            $query = "UPDATE `files` SET `typeoffile`='3',`date`=NOW() WHERE `id`='{$id_w}'";
                            $temptable = "files";
                        }
                        break;
                    default:
                    {//ничего  не делаем
                    }
                }
                if (!("" === $query))//Если запрос не пустой то отправить его в БД
                {
                    //echo "<pre>Запрос не пустой!</pre>";
                    $result = mysqli_query($link, $query)
                    or die("Полка оновлення запису дія work_edit_add_file_2: " . mysqli_error($link));
                    log_action($_POST['action'], $temptable, $id_w);
                }
                //print_r($query);

                header("Location: action.php?action=all_view#id_w" . $id_w);
                */
            }
        }

    }
}