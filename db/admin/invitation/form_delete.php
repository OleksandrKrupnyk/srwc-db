<?php

use zukr\base\Base;
use zukr\base\helpers\FileSystemHelper;
use zukr\log\Log;
use zukr\scanfiles\ScanFilesRepository;

if (($id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)) !== false) {
    try {
        $log = Log::getInstance();
        $scanFile = (new ScanFilesRepository())->findById($id);
        $id_u = $scanFile->id_u ?? 0;
        $realPathName = FileSystemHelper::normalizePath(APP_ROOT_DIR . $scanFile->file);
        $checkFile = file_exists($realPathName);

        if (!$checkFile) {
            Base::$log->error('Не можливо знайти файл ' . $realPathName);
        }

        if ($checkFile && !unlink($realPathName)) {
            throw new Exception('Помилка видалення файлу за файлової системи');
        }

        $query = $scanFile->getDb();
        $delete = $scanFile->delete($query->where('id', $id));

        if (!$delete) {
            throw new Exception('Помилка видалення запису про файл з БД');
        }
        $log->logAction(null, $scanFile::getTableName(), $id);
    } catch (\Throwable $e) {
        Base::$log->error($e->getMessage());
        Base::$session->setFlash('recordSaveMsg', 'Помилка');
        Base::$session->setFlash('recordSaveType', 'error');
    }
}
Go_page('action.php?action=invitation_list');





