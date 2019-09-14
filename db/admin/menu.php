<?php
return [
    [
        'value' => 'Основна',
        'title' => '',
        'href'  => '#',
        'class' => '',
        'items' => [
            [
                'value' => 'Усі роботи',
                'href'  => 'action.php?action=all_view',
                'title' => 'Таблиця з данними про роботи',
            ],
            [
                'value' => 'Списки',
                'href'  => '#',
                'items' => [
                    [
                        'value' => 'Автори',
                        'href'  => 'action.php?action=autor_list',
                        'title' => 'Редагування данних, видалення',
                    ],
                    [
                        'value' => 'Керівники',
                        'href'  => 'action.php?action=leader_list',
                        'title' => 'Редагування данних, видалення',
                    ],
                    [
                        'value' => 'Рецензенти',
                        'href'  => 'action.php?action=reviewer_list',
                        'title' => 'Редагування данних, видалення',
                    ],
                ]
            ],
            [
                'value' => 'Тезиси',
                'href'  => 'action.php?action=tesis_list',
                'title' => 'Таблиця з данними про тезиси',
                'class' => '',
            ],
            [
                'value' => 'Оновити бали рейтингу',
                'href'  => 'action.php?action=review_update',
                'title' => 'Оновлення рейтингу робіт',
            ],

        ],
    ],
    [
        'value' => 'Наповнення БД',
        'title' => 'Занятие ерундой на рабочем месте хорошо развивает боковое зрение, слух, а также бдительность в целом!',
        'href'  => '#',
        'items' => [
            [
                'value' => 'Данні автора',
                'href'  => 'action.php?action=autor_add',
                'title' => 'Внесення в базу даних автора',
            ],
            [
                'value' => 'Данні керівника/рецензента',
                'href'  => 'action.php?action=leader_add',
                'title' => 'Внесення в базу даних керівника/рецензента',
            ],
            [
                'value' => 'Данні роботи',
                'href'  => 'action.php?action=work_add',
                'title' => 'Внесення в базу даних роботи',
            ],
            [
                'value' => 'Зв\'язати роботу',
                'href'  => 'action.php?action=work_link',
                'title' => 'Встановлення зв\'язків робота-&gt;автор, робота-&gt;керівник',
            ],
            [
                'value' => 'Ввести усі данні про роботу',
                'href'  => 'action.php?action=all_add',
                'title' => 'Встановлення зв\'язків робота-&gt;автор, робота-&gt;керівник',
            ],
        ],
    ],
    [
        'value' => 'І повідомлення',
        'title' => '',
        'href'  => '#',
        'class' => '',
        'items' => [
            [
                'value' => 'Список I',
                'href'  => 'action.php?action=univer_invite',
                'title' => 'Друкуєтья у 2-х примірниках (пошта, канцелярія)',
            ],
            [
                'value' => 'Конверти I',
                'href'  => 'lists.php?list=envelope',
                'title' => 'Формат паперу DL. Перевір налаштування принтеру',
            ],
        ],
    ],
    [
        'value' => 'IІ повідомлення',
        'title' => '',
        'href'  => '#',
        'items' => [
            [
                'value' => 'Запрошення та секції',
                'href'  => 'action.php?action=section_invite',
                'title' => 'Відмітки про запрошення та розподіл за секціями',
            ],
            [
                'value' => 'Запрошення журі',
                'href'  => '#',
                'title' => 'Опрацювати запрощення для жюрі',
                'items' => [
                    [
                        'value' => 'Завантаження',
                        'href'  => 'uploadinvitation.php',
                        'title' => 'Завантаження сканувань',
                    ],
                    [
                        'value' => 'Список відмітити',
                        'href'  => 'action.php?action=leader_invit',
                        'title' => 'Відмітити та роздрукувати',
                    ],
                ],
            ],
            [
                'value' => ' Друкувати документи',
                'href'  => '#',
                'title' => '',
                'items' => [
                    [
                        'value' => 'Список II',
                        'href'  => 'lists.php?list=adress2',
                        'title' => 'Друкуєтья у 2-х примірниках (пошта, канцелярія)',
                    ],
                    [
                        'value' => 'Конверти II',
                        'href'  => 'lists.php?list=envelope2',
                        'title' => 'Формат паперу DL. Перевір налаштування принтеру',
                    ],
                    [
                        'value' => 'Листи ректорам',
                        'href'  => 'lists.php?list=invitation_1',
                        'title' => 'Листи друкуються на оффіційному аркуші університету',
                    ],
                    [
                        'value' => 'Додаток 1',
                        'href'  => 'lists.php?list=invitation_2',
                        'title' => 'Формат А4. Список студентів яких запросили',
                    ],
                ]
            ],
        ],
    ],
    [
        'value'  => 'Конференція',
        'title'  => '',
        'href'   => '#',
        'active' => true,
        'class'  => '',
        'items'  => [
            [
                'value' => ' Друкувати до початку конференціх',
                'href'  => '#',
                'title' => '',
                'items' => [
                    [
                        'value' => 'Список авторів для поселення в гуртожитку',
                        'href'  => 'lists.php?list=ahostel',
                    ],
                    [
                        'value' => 'Список керівників для поселення',
                        'href'  => 'lists.php?list=lhostel',
                    ],
                    [
                        'value' => 'Бейджики Авторів',
                        'href'  => 'lists.php?list=badge_autors',
                    ],
                    [
                        'value' => 'Бейджики Керівників',
                        'href'  => 'lists.php?list=badge_leaders',
                    ],

                ]
            ],
            [
                'value' => 'Скелет программи',
                'href'  => 'programa.php',
                'title' => 'Программа конференції',
            ],
            [
                'value' => 'Розподіл секцій за аудиторіями',
                'href'  => 'action.php?action=rooms_edit',
                'title' => 'Розподіл секцій за аудиторіями',
            ],
            [
                'value' => 'Реєстрація учасників конференції',
                'href'  => 'action.php?action=reception_edit',
                'title' => 'Торжественно клянусь, что замышляю только шалость!',
            ],

        ],
    ],
    [
        'value' => 'Завершення',
        'title' => '',
        'href'  => '#',
        'class' => '',
        'items' => [
            [
                'value' => 'Призначення місць',
                'href'  => 'action.php?action=place_edit',
                'title' => '',
            ],
            [
                'value' => 'Розподіл результат',
                'href'  => 'action.php?action=place_view',
                'title' => '',
            ],
            [
                'value' => 'Друкувати',
                'href'  => '#',
                'title' => '',
                'items' => [
                    [
                        'value' => 'Дипломи',
                        'href'  => 'lists.php?list=diploms',
                    ],
                    [
                        'value' => 'Грамоты',
                        'href'  => 'lists.php?list=charters',
                    ],
                    [
                        'value' => 'Подяки',
                        'href'  => 'lists.php?list=gratitudes',
                    ],
                ]
            ],
            [
                'value' => 'Протокол по місцях',
                'href'  => 'action.php?action=protocol',
            ],
            [
                'value' => 'Статистична довідка',
                'href'  => 'action.php?action=statistic',
            ],

        ],
    ],
    [
        'value' => 'Службове',
        'title' => '',
        'href'  => '#',
        'class' => '',
        'items' => [
            [
                'value' => 'Сторінка завантежень запрошень',
                'href'  => 'invitation.php',
                'title' => 'Сторіка користувачів (попередній перегля)',
            ],
            [
                'value' => 'Налаштування',
                'href'  => 'settings.php',
                'title' => '',
            ],
            [
                'value' => 'Журнал дій',
                'href'  => 'log.php',
                'title' => '',
                'class' => 'special',
            ],
            [
                'value' => 'Розсилка',
                'href'  => '#',
                'items'=>[
                    [
                        'value' => 'Електронні запрошення',
                        'href'  => 'action.php?action=sentemail',
                        'title' => 'Редагувати тектс листа та надіслати запрошення',
                        'class' => 'special',
                    ],
                    [
                        'value' => 'Тестова сторінка',
                        'href'  => 'action.php?action=test',
                        'title' => 'Тестова сторінка нічого не недасилається',
                        'class' => 'special',
                    ],
                ]
            ],

        ],
    ]


];