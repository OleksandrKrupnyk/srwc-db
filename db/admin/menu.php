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
                        'href'  => 'action.php?action=author_list',
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
                'href'  => 'action.php?action=author_add',
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
                'href' => 'lists.php?action=envelope',
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
                        'href' => 'lists.php?action=adress2',
                        'title' => 'Друкуєтья у 2-х примірниках (пошта, канцелярія)',
                    ],
                    [
                        'value' => 'Конверти II',
                        'href' => 'lists.php?action=envelope2',
                        'title' => 'Формат паперу DL. Перевір налаштування принтеру',
                    ],
                    [
                        'value' => 'Листи ректорам',
                        'href' => 'lists.php?action=invitation',
                        'title' => 'Листи друкуються на оффіційному аркуші університету',
                    ],
                    [
                        'value' => 'Додаток 1',
                        'href' => 'lists.php?action=invitation2',
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
                        'href' => 'lists.php?action=ahostel',
                    ],
                    [
                        'value' => 'Список керівників для поселення',
                        'href' => 'lists.php?action=lhostel',
                    ],
                    [
                        'value' => 'Бейджики Авторів',
                        'href' => 'lists.php?action=badge_autors',
                    ],
                    [
                        'value' => 'Бейджики Керівників',
                        'href' => 'lists.php?action=badge_leaders',
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
                        'href' => 'lists.php?action=diploms',
                    ],
                    [
                        'value' => 'Грамоты',
                        'href' => 'lists.php?action=charters',
                    ],
                    [
                        'value' => 'Подяки',
                        'href' => 'lists.php?action=gratitudes',
                    ],
                ]
            ],
            [
                'value' => 'Протокол по місцях',
                'href'  => 'action.php?action=protocol_view',
            ],
            [
                'value' => 'Статистична довідка',
                'href'  => 'action.php?action=statistic_view',
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
                'value' => 'Налаштування',
                'href' => 'settings.php',
                'title' => '',
                'class' => 'special',
            ],
            [
                'value' => 'Секції',
                'href' => 'action.php?action=section_list',
                'title' => 'Редагування назв секцій',
                'class' => 'special',
            ],
            [
                'value' => 'Журнал дій',
                'href' => 'log.php',
                'title' => '',
                'class' => 'special',
            ],
            [
                'value' => 'Розсилка',
                'href' => '#',
                'class' => '',
                'items' => [
                    [
                        'value' => 'Електронні запрошення',
                        'href' => 'action.php?action=email_edit',
                        'title' => 'Редагувати тектс листа та надіслати запрошення',
                        'class' => 'special',
                    ],
                    [
                        'value' => 'Тестова сторінка',
                        'href' => 'action.php?action=test_edit',
                        'title' => 'Тестова сторінка нічого не недасилається',
                        'class' => 'special',
                    ],
                ]
            ],

        ],
    ]


];