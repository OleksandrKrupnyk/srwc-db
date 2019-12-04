<?php


namespace zukr\base\html;


/**
 * Class Html
 *
 * @package      zukr\base\html
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class Html
{

    /**
     * @var array the preferred order of attributes in a tag. This mainly affects the order of the attributes
     * that are rendered by [[renderTagAttributes()]].
     */
    public static $attributeOrder = [
        'type',
        'id',
        'class',
        'name',
        'value',

        'href',
        'src',
        'srcset',
        'form',
        'action',
        'method',

        'selected',
        'checked',
        'readonly',
        'disabled',
        'multiple',

        'size',
        'maxlength',
        'width',
        'height',
        'rows',
        'cols',

        'alt',
        'title',
        'rel',
        'media',
    ];
    /**
     * @var array list of void elements (element name => 1)
     * @see http://www.w3.org/TR/html-markup/syntax.html#void-element
     */
    public static $voidElements   = [
        'area' => 1,
        'base' => 1,
        'br' => 1,
        'col' => 1,
        'command' => 1,
        'embed' => 1,
        'hr' => 1,
        'img' => 1,
        'input' => 1,
        'keygen' => 1,
        'link' => 1,
        'meta' => 1,
        'param' => 1,
        'source' => 1,
        'track' => 1,
        'wbr' => 1,
    ];
    public static $dataAttributes = ['data', 'data-ng', 'ng'];

    /**
     * @param       $name
     * @param null  $value
     * @param array $items
     * @param array $options
     * @return string
     */
    public static function select($name, $value = null, $items = [], $options = [])
    {
        $options['name'] = $name;
        $selectOptions = [];

        if (isset($options['prompt'])) {
            $prompt = $options['prompt'];
            unset($options['prompt']);
        } else {
            $prompt = false;
        }

        if ($prompt) {
            $attrs = ['selected' => true, 'disabled' => true, 'value' => '-1'];
            $text = $prompt;
            $selectOptions[] = static::tag('option', $text, $attrs);
        }

        foreach ($items as $key => $text) {
            $attrs = [];
            $attrs['value'] = (string)$key;
            if (!\array_key_exists('selected', $attrs)) {
                $attrs['selected'] = ($value !== null) && ((string)$value === (string)$key);

            }
            $selectOptions[] = static::tag('option', $text, $attrs);
        }

        return static::tag('select', "\n" . implode("\n", $selectOptions) . "\n", $options);
    }


    /**
     * @param $name
     * @param $content
     * @param $options
     * @return string
     */
    /**
     * @param $name
     * @param $content
     * @param $options
     * @return string
     */
    public static function tag($name, $content, $options)
    {
        if ($name === null || $name === false) {
            return $content;
        }

        $html = "<$name" . static::renderTagAttributes($options) . '>';
        return isset(static::$voidElements[strtolower($name)]) ? $html : "$html$content</$name>";
    }


    /**
     * @param $attributes
     * @return string
     */
    /**
     * @param $attributes
     * @return string
     */
    public static function renderTagAttributes($attributes)
    {
        if (count($attributes) > 1) {
            $sorted = [];
            foreach (static::$attributeOrder as $name) {
                if (isset($attributes[$name])) {
                    $sorted[$name] = $attributes[$name];
                }
            }
            $attributes = \array_merge($sorted, $attributes);
        }
        $html = '';
        foreach ($attributes as $name => $value) {
            if (is_bool($value)) {
                if ($value) {
                    $html .= " {$name}";
                }
            } elseif (is_array($value)) {
                if ($name === 'class') {
                    if (empty($value)) {
                        continue;
                    }
                    $html .= " $name=\"" . static::encode(implode(' ', $value)) . '"';
                } elseif ($name === 'style') {
                    if (empty($value)) {
                        continue;
                    }
                    $html .= " $name=\"" . static::encode(static::cssStyleFromArray($value)) . '"';
                } else {
                    $html .= " $name='{$value}' ";
                }
            } elseif ($value !== null) {
                $html .= " $name=\"" . static::encode($value) . '"';
            }
        }
        return $html;
    }

    /**
     * Encodes special characters into HTML entities.
     *
     * @param string $content      the content to be encoded
     * @param bool   $doubleEncode whether to encode HTML entities in `$content`. If false,
     *                             HTML entities in `$content` will not be further encoded.
     * @return string the encoded content
     * @see decode()
     * @see https://secure.php.net/manual/en/function.htmlspecialchars.php
     */
    public static function encode($content, $doubleEncode = true)
    {
        return htmlspecialchars($content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', $doubleEncode);
    }

    /**
     * Decodes special HTML entities back to the corresponding characters.
     * This is the opposite of [[encode()]].
     *
     * @param string $content the content to be decoded
     * @return string the decoded content
     * @see encode()
     * @see https://secure.php.net/manual/en/function.htmlspecialchars-decode.php
     */
    public static function decode($content)
    {
        return htmlspecialchars_decode($content, ENT_QUOTES);
    }

    /**
     * Converts a CSS style array into a string representation.
     *
     * For example,
     *
     * ```php
     * print_r(Html::cssStyleFromArray(['width' => '100px', 'height' => '200px']));
     * // will display: 'width: 100px; height: 200px;'
     * ```
     *
     * @param array $style the CSS style array. The array keys are the CSS property names,
     *                     and the array values are the corresponding CSS property values.
     * @return string the CSS style string. If the CSS style is empty, a null will be returned.
     */
    public static function cssStyleFromArray(array $style)
    {
        $result = '';
        foreach ($style as $name => $value) {
            $result .= "$name: $value; ";
        }
        // return null if empty to avoid rendering the "style" attribute
        return $result === '' ? null : rtrim($result);
    }

}