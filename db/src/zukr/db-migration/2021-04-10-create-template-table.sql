create table template
(
    id int,
    name varchar(255) not null comment 'Назва блоку',
    version varchar(64) default 0.0 not null comment 'Версія блоку',
    content text null comment 'Вміст блоку',
    params varchar(2048) default '' not null comment 'Перелік коснтант, що опроцьовцються блоком',
    description varchar(2048) default '' not null comment 'Опис блоку',
    page_url varchar(2048) default '' null comment 'Адреси сторінок де використовується блок',
    published bool default true not null comment 'Відмітка про активність блоку',
    update_at datetime default NOW() not null comment 'Час останнього редагування сторінки'
)
    comment 'Шаблони блоків тексту';

create unique index template_id_uindex
    on template (id);

create unique index template_name_version_uindex
    on template (name asc, version desc);

create index template_published_index
    on template (published);

alter table template
    add constraint template_pk
        primary key (id);

alter table template modify id int auto_increment;

