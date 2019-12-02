<?php


namespace zukr\base\html;

/**
 * Class HtmlHelper
 *
 * @package      zukr\base\html
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class HtmlHelper
{
    /**
     * @param $options
     * @return string
     */
    public static function course($options): string
    {
        $name = $options['name'] ?? 'course';
        $value = $options['value'] ?? null;
        $items = [1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', 6 => '6'];
        unset($options['name'], $options['value']);
        $options['title'] = "Курс навчання";
        $options['prompt'] = "Курс...";
        return Html::select($name, $value, $items, $options);
    }

    /**
     * @param string     $name
     * @param string     $title
     * @param string|int $value
     * @return string
     */
    public static function checkbox($name, $title, $value): string
    {

        $checked = ($value !== '' && ((int)$value === 1)) ? ' checked ' : '';
        $checkbox = "<input type='hidden' name='{$name}' value='0'><input type='checkbox' name='{$name}' title='{$title}' {$checked} value='1' >";

        return $checkbox;
    }

    /**
     * @param $options
     * @return string
     */
    public static function place($options): string
    {
        $name = $options['name'] ?? 'place';
        $value = $options['value'] ?? null;
        unset($options['name'], $options['value']);
        return Html::select($name, $value, ["D" => "D", "I" => "I", "II" => "II", "III" => "III"],
            ['title' => 'Призове місце:(D-Диплом за участь)', 'required' => true, 'prompt' => 'Місце...']);
    }
}