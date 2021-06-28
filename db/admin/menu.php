<?php
return [
    [
        'value' => '<i class="icofont-page"></i>Основна',
        'title' => '',
        'href'  => '#',
        'class' => '',
        'items' => [
            [
                'value' => '<i class="icofont-papers"></i> Усі роботи',
                'href'  => 'action.php?action=all_view',
                'title' => 'Таблиця з даними про роботи',
            ],
            [
                'value' => '<i class="icofont-listing-number"></i> Списки',
                'href'  => '#',
                'items' => [
                    [
                        'value' => '<i class="icofont-group-students"></i> Автори',
                        'href'  => 'action.php?action=author_list',
                        'title' => 'Редагування даних, видалення',
                    ],
                    [
                        'value' => '<i class="icofont-teacher"></i> Керівники',
                        'href'  => 'action.php?action=leader_list',
                        'title' => 'Редагування даних, видалення',
                    ],
                    [
                        'value' => '<i class="icofont-funky-man"></i> Рецензенти',
                        'href'  => 'action.php?action=reviewer_list',
                        'title' => 'Редагування даних, видалення',
                    ],
                ]
            ],
            [
                'value' => '<i class="icofont-notebook"></i> Тезиси',
                'href'  => 'action.php?action=tesis_list',
                'title' => 'Таблиця з даними про тезиси',
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
        'value' => '<i class="icofont-database"></i> Наповнення БД',
        'title' => 'Занятие ерундой на рабочем месте хорошо развивает боковое зрение, слух, а также бдительность в целом!',
        'href'  => '#',
        'items' => [
            [
                'value' => '<i class="icofont-student"></i> Данні автора',
                'href'  => 'action.php?action=author_add',
                'title' => 'Внесення в базу даних автора',
            ],
            [
                'value' => '<i class="icofont-teacher"></i> Данні керівника/рецензента',
                'href'  => 'action.php?action=leader_add',
                'title' => 'Внесення в базу даних керівника/рецензента',
            ],
            [
                'value' => '<i class="icofont-paper"></i> Данні роботи',
                'href'  => 'action.php?action=work_add',
                'title' => 'Внесення в базу даних роботи',
            ],
            [
                'value' => '<i class="icofont-connection"></i> Зв\'язати роботу',
                'href'  => 'action.php?action=work_link',
                'title' => 'Встановлення зв\'язків робота-&gt;автор, робота-&gt;керівник',
            ],
            [
                'value' => '<i class="icofont-atom"></i> Ввести усі данні про роботу',
                'href'  => 'action.php?action=all_add',
                'title' => 'Встановлення зв\'язків робота-&gt;автор, робота-&gt;керівник',
            ],
        ],
    ],
    [
        'value' => '<i class="icofont-ui-email"></i> І повідомлення',
        'title' => '',
        'href'  => '#',
        'class' => '',
        'items' => [
            [
                'value' => '<i class="icofont-listing-box"></i> Список I',
                'href'  => 'action.php?action=univer_invite',
                'title' => 'Друкується у 2-х примірниках (пошта, канцелярія)',
            ],
            [
                'value' => '<i class="icofont-mail"></i> Конверти I',
                'href' => 'lists.php?action=envelope',
                'title' => 'Формат паперу DL. Перевір налаштування принтеру',
            ],
        ],
    ],
    [
        'value' => '<i class="icofont-mail-box"></i> IІ повідомлення',
        'title' => '',
        'href'  => '#',
        'items' => [
            [
                'value' => '<i class="icofont-golf-cart"></i> Запрошення та секції',
                'href'  => 'action.php?action=section_invite',
                'title' => 'Відмітки про запрошення та розподіл за секціями',
            ],
            [
                'value' => '<i class="icofont-judge"></i> Запрошення журі',
                'href'  => '#',
                'title' => 'Опрацювати запрошення для журі',
                'items' => [
                    [
                        'value' => '<i class="icofont-upload-alt"></i> Завантаження',
                        'href'  => 'action.php?action=invitation_list',
                        'title' => 'Завантаження сканувань',
                    ],
                    [
                        'value' => '<i class="icofont-checked"></i> Список відмітити',
                        'href'  => 'action.php?action=leader_invit',
                        'title' => 'Відмітити та роздрукувати',
                    ],
                ],
            ],
            [
                'value' => '<i class="icofont-print"></i> Друкувати документи',
                'href'  => '#',
                'title' => '',
                'items' => [
                    [
                        'value' => '<i class="icofont-listing-box"></i> Список II',
                        'href' => 'lists.php?action=adress2',
                        'title' => 'Друкується у 2-х примірниках (пошта, канцелярія)',
                    ],
                    [
                        'value' => '<i class="icofont-mail"></i> Конверти II',
                        'href' => 'lists.php?action=envelope2',
                        'title' => 'Формат паперу DL. Перевір налаштування принтеру',
                    ],
                    [
                        'value' => '<i class="icofont-businessman"></i> Листи ректорам',
                        'href' => 'lists.php?action=invitation',
                        'title' => 'Листи друкуються на офіційному аркуші університету',
                    ],
                    [
                        'value' => '<i class="icofont-contact-add"></i> Додаток 1',
                        'href' => 'lists.php?action=invitation2',
                        'title' => 'Формат А4. Список студентів яких запросили',
                    ],
                ]
            ],
        ],
    ],
    [
        'value'  => '<i class="icofont-lawyer-alt-1"></i> Конференція',
        'title'  => '',
        'href'   => '#',
        'active' => true,
        'class'  => '',
        'items'  => [
            [
                'value' => '<i class="icofont-print"></i> Друкувати до початку конференції',
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
                        'href' => 'lists.php?action=badge_authors',
                    ],
                    [
                        'value' => 'Бейджики Керівників',
                        'href' => 'lists.php?action=badge_leaders',
                    ],

                ]
            ],
            [
                'value' => '<i class="icofont-read-book"></i> Скелет програми',
                'href'  => 'programa.php',
                'title' => 'Програма конференції',
            ],
            [
                'value' => '<i class="icofont-university"></i> Розподіл секцій за аудиторіями',
                'href'  => 'action.php?action=rooms_edit',
                'title' => 'Розподіл секцій за аудиторіями',
            ],
            [
                'value' => '<i class="icofont-bell-alt"></i> Реєстрація учасників конференції',
                'href'  => 'action.php?action=reception_edit',
                'title' => 'Торжественно клянусь, что замышляю только шалость!',
            ],

        ],
    ],
    [
        'value' => '<i class="icofont-racing-flag-alt"></i> Завершення',
        'title' => '',
        'href'  => '#',
        'class' => '',
        'items' => [
            [
                'value' => '<i class="icofont-result-sport"></i> Призначення місць',
                'href'  => 'action.php?action=place_edit',
                'title' => '',
            ],
            [
                'value' => '<i class="icofont-score-board"></i> Розподіл результат',
                'href'  => 'action.php?action=place_view',
                'title' => '',
            ],
            [
                'value' => '<i class="icofont-print"></i> Друкувати',
                'href'  => '#',
                'title' => '',
                'items' => [
                    [
                        'value' => '<i class="icofont-certificate"></i> Дипломи',
                        'href' => 'lists.php?action=diploms',
                    ],
                    [
                        'value' => '<i class="icofont-certificate-alt-2"></i> Грамоти',
                        'href' => 'lists.php?action=charters',
                    ],
                    [
                        'value' => '<i class="icofont-certificate-alt-1"></i> Подяки',
                        'href' => 'lists.php?action=gratitudes',
                    ],
                ]
            ],
            [
                'value' => '<i class="icofont-racing-flag"></i> Протокол по місцях',
                'href'  => 'action.php?action=protocol_view',
            ],
            [
                'value' => '<i class="icofont-spreadsheet"></i> Статистична довідка',
                'href'  => 'action.php?action=statistic_view',
            ],

        ],
    ],
    [
        'value' => '<i class="icofont-instrument"></i> Службове',
        'title' => '',
        'href'  => '#',
        'class' => '',
        'items' => [
            [
                'value' => '<i class="icofont-test-tube-alt"></i> Налаштування',
                'href' => 'settings.php',
                'title' => '',
                'class' => 'special',
            ],
            [
                'value' => '<i class="icofont-olympic-logo"></i> Секції',
                'href' => 'action.php?action=section_list',
                'title' => 'Редагування назв секцій',
                'class' => 'special',
            ],
            [
                'value' => '<i class="icofont-microscope-alt"></i> Журнал дій',
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
                        'title' => 'Редагувати текст листа та надіслати запрошення',
                        'class' => 'special',
                    ],
                    [
                        'value' => 'Тестова сторінка',
                        'href' => 'action.php?action=test_edit',
                        'title' => 'Тестова сторінка нічого не надсилається',
                        'class' => 'special',
                    ],
                ]
            ],
            [
                'value' => '<i class="icofont-paper"></i> Шаблони',
                'href' => 'action.php?action=template_list',
                'title' => '',
                'class' => 'special',
            ]

        ],
    ]


];