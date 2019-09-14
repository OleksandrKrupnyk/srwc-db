<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 15:03
 */
// Загрузка файла для работы
global $link;
if (isset($_FILES['file']))//проверяем загрузился ли файл
{
    //print_r($_FILES);
    $file = $_FILES['file']['tmp_name'];
    $file_name = $_FILES['file']['name'];
    $file_size = $_FILES['file']['size'];
    $file_type = $_FILES['file']['type'];
    $error_code = $_FILES['file']['error'];
    $id_w = $_POST['id_w'];
    $typeoffile = $_POST['typeoffile'];
    switch ($typeoffile) {
        case "work": {
            $typeoffile = 0;
        }
            break;
        case "tesis": {
            $typeoffile = 1;
        }
            break;
        case "presentation": {
            $typeoffile = 2;
        }
            break;
        case "information": {
            $typeoffile = 3;
        }
            break;

        default: {
            $typeoffile = 0;
        }
    };
    //$presentation = ($_POST['presentation'] == "")?0:1; /* Старый способ*/
    if ($error_code == 0)//Нет ли ошибок загрузки
    {
        //проверим есть ли католог для материалов работы
        //если нет то создадим его
        if (!file_exists(DIR . $id_w)) {
            if (!mkdir(DIR . $id_w . "/", 0777, true)) {
                die('Помилка при створенні теки для матеріалів роботи...');
            }
        }
        //если он есть то удостоверимся что это каталог
        if (is_dir(DIR . $id_w)) {
            //да это каталог
            //Сфорируем путь для копирования файла
            $file_name = DIR . $id_w . "/" . $file_name;
	    // Если операционная система сервера windows то провести пеобразование имени 		файла
	    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		$fileNameCyrillic = iconv('UTF-8', 'windows-1251',$file_name);
            }else{ // Если же Linux то ничего не делать
	            $fileNameCyrillic = $file_name;
		}
            // Конвертируем имя в кодировку windows-1251
            //$fileNameCyrillic= iconv('UTF-8', 'windows-1251',$file_name);
            //echo $fileNameCyrillic;

            //Создаем пустой файл
            touch($fileNameCyrillic);
            chmod($fileNameCyrillic,0777);
            //скопируем туда файл
            if (!copy($file, $fileNameCyrillic)) echo "<pre>Помилка при копіюванні файлу</pre>";
            else {//Если скопировалось удачно то сформируем запрос в БД на добавление записи в таблицц файлов
                if (0 == $typeoffile) { // Если файл с тектом работы то положим его в архив zip
                    if (!exec("zip -j " . DIR . $id_w . "/id_" . $id_w . "_text.zip  \"" . $fileNameCyrillic ."\""))
                        echo "<pre>Помилка при архівуванні файлу</pre>";//cообщение если ошибка
                    else { //удаление файла после архивирования
                        unlink($fileNameCyrillic);
                    };
                    //Новое имя для внесения в запись БД
                    $file_name = DIR . $id_w . "/id_" . $id_w . "_text.zip";
                }
                $query = "INSERT INTO `files` (`id_w`,`file`,`typeoffile`,`date`)\n"
                    . "VALUES ( '{$id_w}','" . htmlspecialchars($file_name) . "','{$typeoffile}',NOW())";
                mysqli_query($link, "SET NAMES 'utf8'");
                mysqli_query($link, "SET CHARACTER SET 'utf8'");
                //выполним запрос
                $result = mysqli_query($link, $query)
                or die("Полка оновлення запису дія work_edit_add_file: " . mysqli_error($link));
                log_action($_POST['action'], "files", $id_w);
                $query = "";//Очищаем запрос

                switch ($_POST['typeoffile'])//Проверяем тип загруженого файла и формируем запрос в БД для обновления
                {
                    case "tesis": {
                        $query = "UPDATE `works` SET `tesis`='1',`date`=NOW() WHERE `id`='{$id_w}'";
                        $temptable = "works"; // табл.для лога
                    }
                        break;
                    case "presentation": {
                        $query = "UPDATE `files` SET `typeoffile`='2',`date`=NOW() WHERE `id`='{$id_w}'";
                        $temptable = "files";
                    }
                        break;
                    case "information":{
                        $query = "UPDATE `files` SET `typeoffile`='3',`date`=NOW() WHERE `id`='{$id_w}'";
                        $temptable = "files";
                    }
                        break;
                    default: {/*ничего  не делаем*/
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
            }
        }

    }
}
?>