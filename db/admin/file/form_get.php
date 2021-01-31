<?php

use zukr\base\helpers\FileSystemHelper;
use zukr\file\FileHelper;
use zukr\file\FileRepository;

if (($guid = filter_input(INPUT_GET, 'guid', FILTER_SANITIZE_STRING)) !== false) {
    $file = (new FileRepository())->findByGuid($guid);
    $filePath = FileSystemHelper::normalizePath(FileHelper::getInstance()->getRealPath($file));
    if (!file_exists($filePath)) {
        header("HTTP/1.0 404 Not Found");
        exit();
    }
    header('Content-Description: File Transfer');
    header('Content-Type: ' . $file->mime_type);
    header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filePath));
    readfile($filePath);
} else {
    header("HTTP/1.0 404 Not Found");
}
exit();