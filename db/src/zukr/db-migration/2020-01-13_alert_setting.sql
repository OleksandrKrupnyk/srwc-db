alter table settings
    add type enum ('bool', 'string', 'int') default 'bool' null comment 'Тип параметра';
--

INSERT INTO `settings` (parametr, value, description, type)
values ('YEAR', '2020', 'Рік', 'string'),
       ('NYEARS', '2019/2020', 'Навчальний рік', 'string'),
       ('DATEPL', '10.10.2017', 'Дата наказу про місце проведення СНР', 'string'),
       ('ORDERPL', '1364', 'Дата наказу про місце проведення СНР', 'int'),
       ('DATEPO', '18.04.2017', 'Дата наказу про Положення', 'string'),
       ('ORDERPO', '605', 'Номер наказу про положення', 'int'),
       ('N_LEADERS', '1', 'Максимальна кількість керівників на 1 роботу', 'int'),
       ('N_AUTORS', '2', 'Максимальна кількість авторів на 1 роботу', 'int');