<?php
/**
 * Created by PhpStorm.
 * User: krupnik
 * Date: 10.11.17
 * Time: 15:09
 */


use zukr\base\Base;
use zukr\file\FileHelper;
use zukr\file\FileRepository;
use zukr\log\Log;

if (($id_w = filter_input(INPUT_GET, 'id_w', FILTER_VALIDATE_INT)) !== false) {
    $log = Log::getInstance();
    if (($guid = filter_input(INPUT_GET, 'guid', FILTER_SANITIZE_STRING)) !== false) {
        $file = (new FileRepository())->findByGuid($guid);
        $filePath = FileHelper::getInstance()->getRealPath($file);
        $checkFile = false;
        if (file_exists($filePath)) {
            $checkFile = true;
        } else {
            if (file_exists(iconv('UTF-8', 'windows-1251', $filePath))) {
                $checkFile = true;
                $filePath = iconv('UTF-8', 'windows-1251', $filePath);
            }
        }
        if ($checkFile) {
            if (unlink($filePath)) {
                $queryFile = $file->getDb();
                $delete = $file->delete($queryFile->where('guid', $guid));
                if ($delete) {
                    $log->logAction(null, $file::getTableName(), $id_w);
                } else {
                    Base::$log->error('Помилка видалення запису про файл з БД');
                }
            } else {
                Base::$log->error('Помилка видалення файлу за файлової системи ' . $filePath);
            }
        } else {
            Base::$log->error('Не можливо знайти файл ' . $filePath);
        }
    }
    Go_page('action.php?action=all_view#id_w' . $id_w);
}