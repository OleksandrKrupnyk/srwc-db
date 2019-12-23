create index leaders_review_index
    on leaders (review desc);
create index leaders_id_u_index
    on leaders (id_u);
create index files_id_w_index
    on files (id_w);
create index univers_invite_index
    on univers (invite);
create index works_id_u_index
    on works (id_u);
alter table works
    add constraint works_univers_id_fk
        foreign key (id_u) references univers (id)
            on update cascade;
alter table autors
    add constraint autors_univers_id_fk
        foreign key (id_u) references univers (id)
            on update cascade;

