<?php


namespace zukr\base;

/**
 * Class ReplacerService
 *
 * @package zukr\base
 * @author Alex.Krupnik <krupnik_a@ukr.net>
 */
class ReplacerService
{

    /**
     * @var array Сховище вже опрацьованих шаблонів
     */
    private $texts = [];

    /**
     * Виконує заміну підстановок у тексті
     *
     * @param string $text Вхідний текст
     * @param array $inputReplacer Список додаткових підстановок
     * @return string Текст з заміною
     */
    public function makeReplace(string $text, array $inputReplacer = []): string
    {
        if (empty($text)) {
            return '';
        }
        $keyText = \md5($text);
        if (\array_key_exists($keyText, $this->texts)) {
            extract($this->texts[$keyText], EXTR_OVERWRITE);
        } else {
            $matchesInTemplate = $this->getReplacementKeyInTemplate($text);
            $replace = $this->getReplacement($matchesInTemplate);
            $this->texts[$keyText] =
                ['replace' => $replace, 'matchesInTemplate' => $matchesInTemplate];
        }
        $replace = array_merge($replace, $inputReplacer);
        $text = \str_replace(\array_keys($replace), $replace, $text);
        return \preg_replace(
            '/(\{\@[\w\s]*\})/mui',
            '<mark style="color:black;background-color: yellow">$1</mark>',
            $text
        );
    }

    /**
     * Виконує пошук підстановок у тексті
     *
     * @param string $text Вхідний текст
     * @return array Список підстановок
     */
    private function getReplacementKeyInTemplate($text): array
    {
        \preg_match_all('/\{\@[\w\s]*\}/mui', $text, $matches);
        $matches = \array_map('trim', $matches[0]);
        $matches = \array_map('mb_strtolower', $matches);
        return \array_unique($matches);
    }

    /**
     * Повертає список підстановок за замовчуванням
     *
     * @return array Список підстановок
     */
    protected function templateParams(): array
    {
        return [
            '{@date_pl}' => Base::$param['DATEPL'],
            '{@order_pl}' => Base::$param['ORDERPL'],
            '{@date_po}' => Base::$param['DATEPO'],
            '{@order_po}' => Base::$param['ORDERPO'],
            '{@year}' => Base::$param['YEAR'],
            '{@gerb}' => '',

            '{@test}' => function (): string {
                return Base::$param['ORDERPO'];
            },
        ];
    }

    /**
     * Повертає список усіх доступних замін
     *
     * ```php
     * ['{@foo}','{@bar}',...]
     * ```
     *
     * @return array Список замін
     */
    public function getTemplateParams(): array
    {
        return \array_keys($this->templateParams());
    }

    /**
     * Повертає значення замін індексованих за ключем
     * ```php
     *  [
     *      '{@foo}'=>'FOO',
     *      '{@bar}'=>'BAR',
     *
     *  ]
     * ```
     * @return array
     */
    public function getReplacement($templateKeys): array
    {
        $list = [];
        foreach ($this->replacement() as $key => $item) {
            if (\in_array($key, $templateKeys)) {
                $list[$key] = $item;
            }
        }
        return $list;
    }

    /**
     * Генератор значень підстановок
     *
     * @return \Generator
     */
    protected function replacement(): \Generator
    {
        foreach ($this->templateParams() as $key => $couple) {
            if ($couple instanceof \Closure) {
                yield $key => $couple();
            } elseif (\is_string($couple)) {
                yield $key => $couple;
            } elseif ($couple === null) {
                yield $key => 'Не визначено';
            }
        }
    }
}