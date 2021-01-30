<?php


namespace zukr\base\helpers;

/**
 * Class FileSystemHelper
 *
 * @package zukr\base\helpers
 * @author Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class FileSystemHelper
{

    /**
     * Normalizes a file/directory path.
     *
     * The normalization does the following work:
     *
     * - Convert all directory separators into `DIRECTORY_SEPARATOR` (e.g. "\a/b\c" becomes "/a/b/c")
     * - Remove trailing directory separators (e.g. "/a/b/c/" becomes "/a/b/c")
     * - Turn multiple consecutive slashes into a single one (e.g. "/a///b/c" becomes "/a/b/c")
     * - Remove ".." and "." based on their meanings (e.g. "/a/./b/../c" becomes "/a/c")
     *
     * Note: For registered stream wrappers, the consecutive slashes rule
     * and ".."/"." translations are skipped.
     *
     * @param string $path the file/directory path to be normalized
     * @param string $ds the directory separator to be used in the normalized result. Defaults to `DIRECTORY_SEPARATOR`.
     * @return string the normalized file/directory path
     */
    public static function normalizePath($path, $ds = DIRECTORY_SEPARATOR)
    {
        $path = rtrim(strtr($path, '/\\', $ds . $ds), $ds);
        if (strpos($ds . $path, "{$ds}.") === false && strpos($path, "{$ds}{$ds}") === false) {
            return $path;
        }
        // fix #17235 stream wrappers
        foreach (stream_get_wrappers() as $protocol) {
            if (strpos($path, "{$protocol}://") === 0) {
                return $path;
            }
        }
        // the path may contain ".", ".." or double slashes, need to clean them up
        if (strpos($path, "{$ds}{$ds}") === 0 && $ds === '\\') {
            $parts = [$ds];
        } else {
            $parts = [];
        }
        foreach (explode($ds, $path) as $part) {
            if ($part === '..' && !empty($parts) && end($parts) !== '..') {
                array_pop($parts);
            } elseif ($part === '.' || $part === '' && !empty($parts)) {
                continue;
            } else {
                $parts[] = $part;
            }
        }
        $path = implode($ds, $parts);
        return $path === '' ? '.' : $path;
    }


}