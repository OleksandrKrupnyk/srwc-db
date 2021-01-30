alter table files
    add guid varchar(36) default '' not null comment 'GUID file code' after id_w;
alter table files
    add mime_type varchar(127) default '' not null comment 'MIME Type of file' after file;