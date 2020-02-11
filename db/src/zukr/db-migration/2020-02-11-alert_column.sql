alter table log
    modify action_id varchar(50) default 0 not null comment 'С какой записью';