<?php


namespace zukr\template;

/**
 * Class TemplateNameDictionary
 *
 * Клас довідник імен шаблонів
 *
 * @package zukr\template
 * @author Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class TemplateNameDictionary
{
    /**
     * Сторінка завантаження запрошень на конференцію учасниками (журі)
     */
    public const INVITATION_PAGE_DESCRIPTION = 'INVITATION_PAGE_DESCRIPTION';
    /**
     * Конверт Першого інформаційного повідомлення
     */
    public const ENVELOP = 'ENVELOP';
    /**
     * Конверт Другого  інформаційного повідомлення
     */
    public const ENVELOP2 = 'ENVELOP2';
    /**
     * Листи запрошень ректорам університетів
     */
    public const INVITATION = 'INVITATION';
    /**
     * Списки студентів запрошених до участі у підсумковій конференції
     */
    public const INVITATION2 = 'INVITATION2';

    public static function getAll(): array
    {
        return (new \ReflectionClass(self::class))->getConstants();
    }
}