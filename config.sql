/* Poista halutessasi vanha tietokanta*/
drop database if exists c0tapi01;

/* Luo tietokanta */
create database c0tapi01;

/* Käytä ja suorita tähän tietokantaan */
use c0tapi01;

/* Luo käyttäjä taulu */
create table users (
    user_id int primary key auto_increment,
    username varchar(20) not null UNIQUE,
    password varchar(200) not null,
    first_name varchar(20) not null
);

-- ------------------------------------------------------------------------------------

/* Luo taulun käyttäjän lempiasioista taulu */
create table favorite_things (
    id int primary key auto_increment,
    username varchar(20) not null,
    favorite_color varchar(20) not null,
    favorite_food varchar(20) not null,
    favorite_drink varchar(20) not null,
    foreign key (username) references users(username)
    on delete cascade
);

-- ------------------------------------------------------------------------------------

/* POST ja GET komennot:

Luo uusi käyttäjä
{
    "username":"Nepa",
    "password":"Salainensana",
    "first_name":"Nestori"
}

{
    "username":"Pastori",
    "password":"Erittäinsalainen",
    "first_name":"Pasi"
}

Lisää kirjautuneelle käyttäjälle yksityistietoja
{
    "favorite_color":"valkoinen",
    "favorite_food":"pizza",
    "favorite_drink":"olut"
}
*/