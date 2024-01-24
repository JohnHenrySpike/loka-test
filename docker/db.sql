create table users
(
    id        int auto_increment
        primary key,
    username  varchar(255)         not null,
    email     varchar(255)         not null,
    validts   timestamp            null,
    confirmed tinyint(1) default 0 not null,
    checked   tinyint(1) default 0 not null,
    constraint users_pk2
        unique (email)
);

create index users_validts_index
    on users (validts);

create table notifications
(
    id         int auto_increment
        primary key,
    username   varchar(255)                        not null,
    email      varchar(255)                        not null,
    failed     tinyint(1)                          null,
    created_at timestamp default CURRENT_TIMESTAMP not null
);