<?php

use zukr\base\Base;
use zukr\base\helpers\FileSystemHelper;
use zukr\log\Log;
use zukr\scanfiles\ScanFiles;

$scanFile = new ScanFiles();
$log = Log::getInstance();
$_FILE = $_FILES['file'];
try {
    if (!isset($_FILE)) {
        throw new Exception('Cannot access to  variable $_FILE');
    }
    if (!\is_uploaded_file($_FILE['tmp_name'])) {
        throw new Exception('File was not upload to server');
    }
    if ($_FILE['error'] !== 0) {
        throw new Exception('Error during uploading file. Error code id: ' . $_FILE['error']);
    }
    $scanFile->load($_POST);
    $id_u = $scanFile->id_u;
    $file = $_FILE['tmp_name'];
    $file_name = $_FILE['name'];
    $file_size = $_FILE['size'];
    $file_type = $_FILE['type'];
    $file_md5 = \md5_file($file);

    if (!file_exists(IMGDIR . $id_u)) {
        if (!mkdir($concurrentDirectory = IMGDIR . $id_u . DIRECTORY_SEPARATOR, 0777, true)
            && !is_dir($concurrentDirectory)
        ) {
            throw new Exception('Error creating directory on path: ' . $concurrentDirectory);
        }
    }
    if (!\is_dir(IMGDIR . $id_u)) {
        throw new Exception(IMGDIR . $id_u . ' Not a directory');
    }
    if (!\file_exists(IMGDIR . $id_u . DIRECTORY_SEPARATOR . 'index.php')) {
        \file_put_contents(
            IMGDIR . $id_u . DIRECTORY_SEPARATOR . 'index.php',
            "<?php\nheader(\"HTTP/1.0 404 Not Found\");"
        );
    }
    $file_name = IMGDIR . $id_u . DIRECTORY_SEPARATOR . \date('Ymd_His') . '_' . $file_name;
    $realPathName = FileSystemHelper::normalizePath(APP_ROOT_DIR . $file_name);
    if (!\move_uploaded_file($file, $realPathName)) {
        throw new Exception('Error on moving uploaded file:' . $file . ' to storage: ' . $realPathName);
    }

    $scanFile->filename = 'Запрошення_' . \basename($file_name);
    $scanFile->md5sum = $file_md5;
    $scanFile->save();
    $log->logAction(null, $scanFile::getTableName(), $scanFile->id_u);

} catch (\Throwable $e) {
    Base::$log->error($e->getMessage());
    Base::$session->setFlash('recordSaveMsg', 'Помилка');
    Base::$session->setFlash('recordSaveType', 'error');
}
Go_page('action.php?action=invitation_list');

