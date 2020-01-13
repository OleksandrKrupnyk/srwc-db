<?php


namespace zukr\base\helpers;

/**
 * Class StringHelper
 *
 * @package      zukr\base\helpers
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class StringHelper
{

    /**
     * Returns the number of bytes in the given string.
     * This method ensures the string is treated as a byte array by using `mb_strlen()`.
     *
     * @param string $string the string being measured for length
     * @return int the number of bytes in the given string.
     */
    public static function byteLength($string): string
    {
        return \mb_strlen($string, '8bit');
    }

    /**
     * Returns the portion of string specified by the start and length parameters.
     * This method ensures the string is treated as a byte array by using `mb_substr()`.
     *
     * @param string $string the input string. Must be one character or longer.
     * @param int    $start  the starting position
     * @param int    $length the desired portion length. If not specified or `null`, there will be
     *                       no limit on length i.e. the output will be until the end of the string.
     * @return string the extracted part of string, or FALSE on failure or an empty string.
     * @see https://secure.php.net/manual/en/function.substr.php
     */
    public static function byteSubstr($string, $start, $length = null): string
    {
        return \mb_substr($string, $start, $length ?? \mb_strlen($string, '8bit'), '8bit');
    }

    /**
     * Returns the trailing name component of a path.
     * This method is similar to the php function `basename()` except that it will
     * treat both \ and / as directory separators, independent of the operating system.
     * This method was mainly created to work on php namespaces. When working with real
     * file paths, php's `basename()` should work fine for you.
     * Note: this method is not aware of the actual filesystem, or path components such as "..".
     *
     * @param string $path   A path string.
     * @param string $suffix If the name component ends in suffix this will also be cut off.
     * @return string the trailing name component of the given path.
     * @see https://secure.php.net/manual/en/function.basename.php
     */
    public static function basename($path, $suffix = ''): string
    {
        if (($len = \mb_strlen($suffix)) > 0 && mb_substr($path, -$len) === $suffix) {
            $path = \mb_substr($path, 0, -$len);
        }
        $path = \rtrim(\str_replace('\\', '/', $path), '/\\');
        if (($pos = \mb_strrpos($path, '/')) !== false) {
            return \mb_substr($path, $pos + 1);
        }

        return $path;
    }

    /**
     * Returns parent directory's path.
     * This method is similar to `dirname()` except that it will treat
     * both \ and / as directory separators, independent of the operating system.
     *
     * @param string $path A path string.
     * @return string the parent directory's path.
     * @see https://secure.php.net/manual/en/function.basename.php
     */
    public static function dirname($path): string
    {
        $pos = \mb_strrpos(\str_replace('\\', '/', $path), '/');
        if ($pos !== false) {
            return \mb_substr($path, 0, $pos);
        }

        return '';
    }

    /**
     * Truncates a string to the number of characters specified.
     *
     * @param string $string   The string to truncate.
     * @param int    $length   How many characters from original string to include into truncated string.
     * @param string $suffix   String to append to the end of truncated string.
     * @param string $encoding The charset to use, defaults to charset currently used by application.
     *                         This parameter is available since version 2.0.1.
     * @return string the truncated string.
     */
    public static function truncate($string, $length, $suffix = '...', $encoding = null): string
    {
        if ($encoding === null) {
            $encoding = 'UTF-8';
        }

        if (\mb_strlen($string, $encoding) > $length) {
            return \rtrim(mb_substr($string, 0, $length, $encoding)) . $suffix;
        }

        return $string;
    }

    /**
     * Truncates a string to the number of words specified.
     *
     * @param string $string The string to truncate.
     * @param int    $count  How many words from original string to include into truncated string.
     * @param string $suffix String to append to the end of truncated string.
     *                       This parameter is available since version 2.0.1.
     * @return string the truncated string.
     */
    public static function truncateWords($string, $count, $suffix = '...'): string
    {
        $words = \preg_split('/(\s+)/u', \trim($string), null, PREG_SPLIT_DELIM_CAPTURE);
        if (count($words) / 2 > $count) {
            return \implode('', \array_slice($words, 0, ($count * 2) - 1)) . $suffix;
        }

        return $string;
    }

    /**
     * Check if given string starts with specified substring.
     * Binary and multibyte safe.
     *
     * @param string $string        Input string
     * @param string $with          Part to search inside the $string
     * @param bool   $caseSensitive Case sensitive search. Default is true. When case sensitive is enabled, $with must exactly match the starting of the string in order to get a true value.
     * @return bool Returns true if first input starts with second input, false otherwise
     */
    public static function startsWith($string, $with, $caseSensitive = true): bool
    {
        if (!$bytes = static::byteLength($with)) {
            return true;
        }
        if ($caseSensitive) {
            return strncmp($string, $with, $bytes) === 0;

        }
        $encoding = 'UTF-8';
        return \mb_strtolower(\mb_substr($string, 0, $bytes, '8bit'), $encoding) === \mb_strtolower($with, $encoding);
    }

    /**
     * Check if given string ends with specified substring.
     * Binary and multibyte safe.
     *
     * @param string $string        Input string to check
     * @param string $with          Part to search inside of the $string.
     * @param bool   $caseSensitive Case sensitive search. Default is true. When case sensitive is enabled, $with must exactly match the ending of the string in order to get a true value.
     * @return bool Returns true if first input ends with second input, false otherwise
     */
    public static function endsWith($string, $with, $caseSensitive = true): bool
    {
        if (!$bytes = static::byteLength($with)) {
            return true;
        }
        if ($caseSensitive) {
            // Warning check, see https://secure.php.net/manual/en/function.substr-compare.php#refsect1-function.substr-compare-returnvalues
            if (static::byteLength($string) < $bytes) {
                return false;
            }

            return \substr_compare($string, $with, -$bytes, $bytes) === 0;
        }

        $encoding = 'UTF-8';
        return \mb_strtolower(\mb_substr($string, -$bytes, \mb_strlen($string, '8bit'), '8bit'), $encoding) === mb_strtolower($with, $encoding);
    }

    /**
     * Explodes string into array, optionally trims values and skips empty ones.
     *
     * @param string $string    String to be exploded.
     * @param string $delimiter Delimiter. Default is ','.
     * @param mixed  $trim      Whether to trim each element. Can be:
     *                          - boolean - to trim normally;
     *                          - string - custom characters to trim. Will be passed as a second argument to `trim()` function.
     *                          - callable - will be called for each value instead of trim. Takes the only argument - value.
     * @param bool   $skipEmpty Whether to skip empty strings between delimiters. Default is false.
     * @return array
     * @since 2.0.4
     */
    public static function explode($string, $delimiter = ',', $trim = true, $skipEmpty = false): array
    {
        $result = explode($delimiter, $string);
        if ($trim !== false) {
            if ($trim === true) {
                $trim = 'trim';
            } elseif (!\is_callable($trim)) {
                $trim = static function ($v) use ($trim) {
                    return \trim($v, $trim);
                };
            }
            $result = \array_map($trim, $result);
        }
        if ($skipEmpty) {
            // Wrapped with array_values to make array keys sequential after empty values removing
            $result = \array_values(\array_filter($result, static function ($value) {
                return $value !== '';
            }));
        }

        return $result;
    }

    /**
     * Counts words in a string.
     *
     * @param string $string
     * @return int
     * @since 2.0.8
     *
     */
    public static function countWords($string): int
    {
        return \count(\preg_split('/\s+/u', $string, null, PREG_SPLIT_NO_EMPTY));
    }

    /**
     * Returns string representation of number value with replaced commas to dots, if decimal point
     * of current locale is comma.
     *
     * @param int|float|string $value
     * @return string
     * @since 2.0.11
     */
    public static function normalizeNumber($value): string
    {
        $value = (string)$value;

        $localeInfo = \localeconv();
        $decimalSeparator = $localeInfo['decimal_point'] ?? null;

        if ($decimalSeparator !== null && $decimalSeparator !== '.') {
            $value = \str_replace($decimalSeparator, '.', $value);
        }

        return $value;
    }

    /**
     * Encodes string into "Base 64 Encoding with URL and Filename Safe Alphabet" (RFC 4648).
     *
     * > Note: Base 64 padding `=` may be at the end of the returned string.
     * > `=` is not transparent to URL encoding.
     *
     * @see   https://tools.ietf.org/html/rfc4648#page-7
     * @param string $input the string to encode.
     * @return string encoded string.
     * @since 2.0.12
     */
    public static function base64UrlEncode($input): string
    {
        return \strtr(\base64_encode($input), '+/', '-_');
    }

    /**
     * Decodes "Base 64 Encoding with URL and Filename Safe Alphabet" (RFC 4648).
     *
     * @see   https://tools.ietf.org/html/rfc4648#page-7
     * @param string $input encoded string.
     * @return string decoded string.
     * @since 2.0.12
     */
    public static function base64UrlDecode($input): string
    {
        return \base64_decode(\strtr($input, '-_', '+/'));
    }

    /**
     * Safely casts a float to string independent of the current locale.
     *
     * The decimal separator will always be `.`.
     *
     * @param float|int $number a floating point number or integer.
     * @return string the string representation of the number.
     * @since 2.0.13
     */
    public static function floatToString($number): string
    {
        // . and , are the only decimal separators known in ICU data,
        // so its safe to call str_replace here
        return \str_replace(',', '.', (string)$number);
    }

    /**
     * Checks if the passed string would match the given shell wildcard pattern.
     * This function emulates [[fnmatch()]], which may be unavailable at certain environment, using PCRE.
     *
     * @param string $pattern the shell wildcard pattern.
     * @param string $string  the tested string.
     * @param array  $options options for matching. Valid options are:
     *
     * - caseSensitive: bool, whether pattern should be case sensitive. Defaults to `true`.
     * - escape: bool, whether backslash escaping is enabled. Defaults to `true`.
     * - filePath: bool, whether slashes in string only matches slashes in the given pattern. Defaults to `false`.
     *
     * @return bool whether the string matches pattern or not.
     * @since 2.0.14
     */
    public static function matchWildcard($pattern, $string, $options = []): bool
    {
        if ($pattern === '*' && empty($options['filePath'])) {
            return true;
        }

        $replacements = [
            '\\\\\\\\' => '\\\\',
            '\\\\\\*' => '[*]',
            '\\\\\\?' => '[?]',
            '\*' => '.*',
            '\?' => '.',
            '\[\!' => '[^',
            '\[' => '[',
            '\]' => ']',
            '\-' => '-',
        ];

        if (isset($options['escape']) && !$options['escape']) {
            unset($replacements['\\\\\\\\'], $replacements['\\\\\\*'], $replacements['\\\\\\?']);
        }

        if (!empty($options['filePath'])) {
            $replacements['\*'] = '[^/\\\\]*';
            $replacements['\?'] = '[^/\\\\]';
        }

        $pattern = \strtr(preg_quote($pattern, '#'), $replacements);
        $pattern = '#^' . $pattern . '$#us';

        if (isset($options['caseSensitive']) && !$options['caseSensitive']) {
            $pattern .= 'i';
        }

        return \preg_match($pattern, $string) === 1;
    }

    /**
     * This method provides a unicode-safe implementation of built-in PHP function `ucfirst()`.
     *
     * @param string $string   the string to be proceeded
     * @param string $encoding Optional, defaults to "UTF-8"
     * @return string
     * @see   https://secure.php.net/manual/en/function.ucfirst.php
     * @since 2.0.16
     */
    public static function mb_ucfirst($string, $encoding = 'UTF-8'): string
    {
        $firstChar = \mb_substr($string, 0, 1, $encoding);
        $rest = \mb_substr($string, 1, null, $encoding);

        return \mb_strtoupper($firstChar, $encoding) . $rest;
    }

    /**
     * This method provides a unicode-safe implementation of built-in PHP function `ucwords()`.
     *
     * @param string $string   the string to be proceeded
     * @param string $encoding Optional, defaults to "UTF-8"
     * @return string
     * @see   https://secure.php.net/manual/en/function.ucwords.php
     * @since 2.0.16
     */
    public static function mb_ucwords($string, $encoding = 'UTF-8'): string
    {
        $words = \preg_split("/\s/u", $string, -1, PREG_SPLIT_NO_EMPTY);

        $titelized = \array_map(static function ($word) use ($encoding) {
            return static::mb_ucfirst($word, $encoding);
        }, $words);

        return \implode(' ', $titelized);
    }

    /**
     * Converts a CamelCase name into an ID in lowercase.
     * Words in the ID may be concatenated using the specified character (defaults to '-').
     * For example, 'PostTag' will be converted to 'post-tag'.
     *
     * @param string      $name      the string to be converted
     * @param string      $separator the character used to concatenate the words in the ID
     * @param bool|string $strict    whether to insert a separator between two consecutive uppercase chars, defaults to false
     * @return string the resulting ID
     */
    public static function camel2id($name, $separator = '-', $strict = false)
    {
        $regex = $strict ? '/\p{Lu}/u' : '/(?<!\p{Lu})\p{Lu}/u';
        if ($separator === '_') {
            return \mb_strtolower(\trim(\preg_replace($regex, '_\0', $name), '_'), self::encoding());
        }

        return \mb_strtolower(\trim(\str_replace('_', $separator, \preg_replace($regex, $separator . '\0', $name)), $separator), self::encoding());
    }

    /**
     * Converts an ID into a CamelCase name.
     * Words in the ID separated by `$separator` (defaults to '-') will be concatenated into a CamelCase name.
     * For example, 'post-tag' is converted to 'PostTag'.
     *
     * @param string $id        the ID to be converted
     * @param string $separator the character used to separate the words in the ID
     * @return string the resulting CamelCase name
     */
    public static function id2camel($id, $separator = '-'): string
    {
        return \str_replace(' ', '', StringHelper::mb_ucwords(\str_replace($separator, ' ', $id), self::encoding()));
    }


    /**
     * Converts an underscored or CamelCase word into a English
     * sentence.
     *
     * @param string $words
     * @param bool   $ucAll whether to set all words to uppercase
     * @return string
     */
    public static function titleize($words, $ucAll = false): string
    {
        $words = static::humanize(static::underscore($words), $ucAll);

        return $ucAll ? StringHelper::mb_ucwords($words, self::encoding()) : StringHelper::mb_ucfirst($words, self::encoding());
    }

    /**
     * Returns given word as CamelCased.
     *
     * Converts a word like "send_email" to "SendEmail". It
     * will remove non alphanumeric character from the word, so
     * "who's online" will be converted to "WhoSOnline".
     *
     * @param string $word the word to CamelCase
     * @return string
     * @see variablize()
     */
    public static function camelize($word): string
    {
        return \str_replace(' ', '', StringHelper::mb_ucwords(\preg_replace('/[^\pL\pN]+/u', ' ', $word), self::encoding()));
    }

    /**
     * Converts a CamelCase name into space-separated words.
     * For example, 'PostTag' will be converted to 'Post Tag'.
     *
     * @param string $name    the string to be converted
     * @param bool   $ucwords whether to capitalize the first letter in each word
     * @return string the resulting words
     */
    public static function camel2words($name, $ucwords = true): string
    {
        $label = \mb_strtolower(\trim(\str_replace([
            '-',
            '_',
            '.',
        ], ' ', \preg_replace('/(?<!\p{Lu})(\p{Lu})|(\p{Lu})(?=\p{Ll})/u', ' \0', $name))), self::encoding());

        return $ucwords ? StringHelper::mb_ucwords($label, self::encoding()) : $label;
    }

    /**
     * @return string
     */
    private static function encoding(): string
    {
        return 'UTF-8';
    }

    /**
     *  Функция возвращает строку с левой стороны от строки
     *
     * @param string $str
     * @param int    $num
     * @return string
     */
    public static function left($str, $num)
    {
        return \mb_substr($str, 0, $num);
    }

    /**
     *  Функция возвращает строку с правой стороны от строки
     *
     * @param string $str
     * @param int    $num
     * @return string
     */
    public static function right($str, $num)
    {
        $len = \strlen($str);
        return \mb_substr($str, $len - $num, $len);
    }
}