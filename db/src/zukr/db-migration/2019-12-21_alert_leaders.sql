Update leaders
set id_tzmember = 0
where id_tzmember IS NULL;

alter table leaders
    modify id_tzmember int default '0' not null comment 'Логин пользователя';


update leaders set page = '' where page is null;

alter table leaders
    modify page varchar(4096) default '' not null comment  ' Сторінка'
