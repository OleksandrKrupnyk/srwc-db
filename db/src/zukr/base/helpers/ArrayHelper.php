<?php


namespace zukr\base\helpers;

/**
 * Class ArrayHelper
 *
 * @package      zukr\base\helpers
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class ArrayHelper
{

    /**
     * @param array  $array
     * @param string $groupKey
     * @return array
     */
    public static function group(array $array, string $groupKey): array
    {
        if (empty($array)) {
            return [];
        }
        $resultArray = [];
        foreach ($array as $item) {
            $key = $item[$groupKey];

            $resultArray[$key][] = $item;
        }
        return $resultArray;
    }

    /**
     * Сортує масив
     *
     * @param array $array Список для сортування
     */
    public static function asort(&$array): void
    {
        $collator = new \Collator('uk_UA.UTF-8');
        $collator->asort($array);
    }

    /**
     * @param      $array
     * @param      $from
     * @param      $to
     * @param null $group
     * @return array
     */
    public static function map($array, $from, $to, $group = null)
    {
        $result = [];
        foreach ($array as $element) {
            $key = static::getValue($element, $from) ?? $from;
            $value = static::getValue($element, $to);
            if ($group !== null) {
                $result[static::getValue($element, $group)][$key] = $value;
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * @param      $array
     * @param      $key
     * @param null $default
     * @return |null
     */
    public static function getValue($array, $key, $default = null)
    {
        if ($key instanceof \Closure) {
            return $key($array, $default);
        }

        if (\is_array($key)) {
            $lastKey = \array_pop($key);
            foreach ($key as $keyPart) {
                $array = static::getValue($array, $keyPart);
            }
            $key = $lastKey;
        }

        if (is_array($array) && (isset($array[$key]) || \array_key_exists($key, $array))) {
            return $array[$key];
        }

        if (($pos = \strrpos($key, '.')) !== false) {
            $array = static::getValue($array, \substr($key, 0, $pos), $default);
            $key = \substr($key, $pos + 1);
        }

        if (\is_object($array)) {
            // this is expected to fail if the property does not exist, or __get() is not implemented
            // it is not reliably possible to check whether a property is accessible beforehand
            return $array->$key;
        } elseif (\is_array($array)) {
            return (isset($array[$key]) || \array_key_exists($key, $array)) ? $array[$key] : $default;
        }

        return $default;
    }

    /**
     * Merges two or more arrays into one recursively.
     * If each array has an element with the same string key value, the latter
     * will overwrite the former (different from array_merge_recursive).
     * Recursive merging will be conducted if both arrays have an element of array
     * type and are having the same key.
     * For integer-keyed elements, the elements from the latter array will
     * be appended to the former array.
     * You can use [[UnsetArrayValue]] object to unset value from previous array or
     * [[ReplaceArrayValue]] to force replace former value instead of recursive merging.
     *
     * @param array $a array to be merged to
     * @param array $b array to be merged from. You can specify additional
     *                 arrays via third argument, fourth argument etc.
     * @return array the merged array (the original arrays are not changed.)
     */
    public static function merge($a, $b)
    {
        $args = \func_get_args();
        $res = \array_shift($args);
        while (!empty($args)) {
            foreach (\array_shift($args) as $k => $v) {
                if ($v instanceof UnsetArrayValue) {
                    unset($res[$k]);
                } elseif ($v instanceof ReplaceArrayValue) {
                    $res[$k] = $v->value;
                } elseif (\is_int($k)) {
                    if (\array_key_exists($k, $res)) {
                        $res[] = $v;
                    } else {
                        $res[$k] = $v;
                    }
                } elseif (\is_array($v) && isset($res[$k]) && \is_array($res[$k])) {
                    $res[$k] = static::merge($res[$k], $v);
                } else {
                    $res[$k] = $v;
                }
            }
        }

        return $res;
    }
    /**
     * Indexes and/or groups the array according to a specified key.
     * The input should be either multidimensional array or an array of objects.
     *
     * The $key can be either a key name of the sub-array, a property name of object, or an anonymous
     * function that must return the value that will be used as a key.
     *
     * $groups is an array of keys, that will be used to group the input array into one or more sub-arrays based
     * on keys specified.
     *
     * If the `$key` is specified as `null` or a value of an element corresponding to the key is `null` in addition
     * to `$groups` not specified then the element is discarded.
     *
     * For example:
     *
     * ```php
     * $array = [
     *     ['id' => '123', 'data' => 'abc', 'device' => 'laptop'],
     *     ['id' => '345', 'data' => 'def', 'device' => 'tablet'],
     *     ['id' => '345', 'data' => 'hgi', 'device' => 'smartphone'],
     * ];
     * $result = ArrayHelper::index($array, 'id');
     * ```
     *
     * The result will be an associative array, where the key is the value of `id` attribute
     *
     * ```php
     * [
     *     '123' => ['id' => '123', 'data' => 'abc', 'device' => 'laptop'],
     *     '345' => ['id' => '345', 'data' => 'hgi', 'device' => 'smartphone']
     *     // The second element of an original array is overwritten by the last element because of the same id
     * ]
     * ```
     *
     * An anonymous function can be used in the grouping array as well.
     *
     * ```php
     * $result = ArrayHelper::index($array, function ($element) {
     *     return $element['id'];
     * });
     * ```
     *
     * Passing `id` as a third argument will group `$array` by `id`:
     *
     * ```php
     * $result = ArrayHelper::index($array, null, 'id');
     * ```
     *
     * The result will be a multidimensional array grouped by `id` on the first level, by `device` on the second level
     * and indexed by `data` on the third level:
     *
     * ```php
     * [
     *     '123' => [
     *         ['id' => '123', 'data' => 'abc', 'device' => 'laptop']
     *     ],
     *     '345' => [ // all elements with this index are present in the result array
     *         ['id' => '345', 'data' => 'def', 'device' => 'tablet'],
     *         ['id' => '345', 'data' => 'hgi', 'device' => 'smartphone'],
     *     ]
     * ]
     * ```
     *
     * The anonymous function can be used in the array of grouping keys as well:
     *
     * ```php
     * $result = ArrayHelper::index($array, 'data', [function ($element) {
     *     return $element['id'];
     * }, 'device']);
     * ```
     *
     * The result will be a multidimensional array grouped by `id` on the first level, by the `device` on the second one
     * and indexed by the `data` on the third level:
     *
     * ```php
     * [
     *     '123' => [
     *         'laptop' => [
     *             'abc' => ['id' => '123', 'data' => 'abc', 'device' => 'laptop']
     *         ]
     *     ],
     *     '345' => [
     *         'tablet' => [
     *             'def' => ['id' => '345', 'data' => 'def', 'device' => 'tablet']
     *         ],
     *         'smartphone' => [
     *             'hgi' => ['id' => '345', 'data' => 'hgi', 'device' => 'smartphone']
     *         ]
     *     ]
     * ]
     * ```
     *
     * @param array $array the array that needs to be indexed or grouped
     * @param string|\Closure|null $key the column name or anonymous function which result will be used to index the array
     * @param string|string[]|\Closure[]|null $groups the array of keys, that will be used to group the input array
     * by one or more keys. If the $key attribute or its value for the particular element is null and $groups is not
     * defined, the array element will be discarded. Otherwise, if $groups is specified, array element will be added
     * to the result array without any key. This parameter is available since version 2.0.8.
     * @return array the indexed and/or grouped array
     */
    public static function index($array, $key, $groups = [])
    {
        $result = [];
        $groups = (array) $groups;

        foreach ($array as $element) {
            $lastArray = &$result;

            foreach ($groups as $group) {
                $value = static::getValue($element, $group);
                if (!array_key_exists($value, $lastArray)) {
                    $lastArray[$value] = [];
                }
                $lastArray = &$lastArray[$value];
            }

            if ($key === null) {
                if (!empty($groups)) {
                    $lastArray[] = $element;
                }
            } else {
                $value = static::getValue($element, $key);
                if ($value !== null) {
                    if (is_float($value)) {
                        $value = StringHelper::floatToString($value);
                    }
                    $lastArray[$value] = $element;
                }
            }
            unset($lastArray);
        }

        return $result;
    }
}