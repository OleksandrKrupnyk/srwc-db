<?php


namespace zukr\base\html;

use zukr\base\helpers\StringHelper;

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
        $options['title'] = 'Курс навчання';
        $options['id'] = 'author-curse';
        $options['prompt'] = 'Курс...';
        return Html::select($name, $value, $items, $options);
    }

    /**
     * @param string     $name
     * @param string     $title
     * @param string|int $value
     * @param null       $id
     * @return string
     */
    public static function checkbox($name, $title, $value, $id = null): string
    {
        $id = $id != null ? 'id=' . $id : '';
        $checked = ($value !== '' && ((int)$value === 1)) ? ' checked ' : '';
        $checkbox = "<input type='hidden' name='{$name}' value='0'><input type='checkbox' name='{$name}' title='{$title}' {$checked} value='1' {$id}>";
        return $checkbox;
    }

    /**
     * @param string     $name
     * @param string     $title
     * @param string|int $value
     * @param null       $id
     * @return string
     * @throws \Exception
     */
    public static function checkboxStyled($name, $title, $value, $id = null): string
    {
        $id = $id ?? 'chkbox_'.random_int(1000, 2000);
        $checked = ($value !== '' && ((int)$value === 1)) ? ' checked=true ' : '';
        $checkbox = "<input type='hidden' name='{$name}' value='0'>
<input type='checkbox' name='{$name}' title='{$title}' {$checked} style='display:none' value='1'  id={$id} />
<label for='{$id}' class='toggle'><span></span></label>";
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

    /**
     * Список файлів
     * @param array $files
     * @return string
     */
    public static function listFiles(array $files)
    {
        if (empty($files)) {
            return '';
        }
        $list = [];
        foreach ($files as $file) {
            $fullFileName = StringHelper::basename($file['file']);
            $fileNameParts = explode('.', $fullFileName);
            $fileExtension = end($fileNameParts);
            $truncateFileName = StringHelper::truncate($fullFileName, 30);

            $link1 = Html::a(
                $truncateFileName,
                "action.php?action=file_get&guid={$file['guid']}", [
                'class' => "link-file",
                'title' => $fullFileName,
                'data-ext' => $fileExtension,
                'style' => "margin-right:10px"
            ]);
            $link2 = Html::a('', "action.php?action=file_remove&id_w={$file['id_w']}&guid={$file['guid']}",
                ['title' => 'Видалити файл', 'class' => 'link-delete-file']);
            $list [] = Html::tag('li', $link1 . $link2, []);
        }
        return '<details><summary>Файли</summary><ol>' . implode(PHP_EOL, $list) . '</ol></details>';
    }
}