alter table tz_members comment 'Users';

alter table tz_members
    add is_admin enum('1', '0') default '0' not null;
