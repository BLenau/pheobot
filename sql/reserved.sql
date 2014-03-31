-- Creates the table used to store reserved command names for the bot.
-- Brian M. Lenau
-- 2014/03/10
USE `pheobot`;

DROP TABLE IF EXISTS reserved;
CREATE TABLE  reserved (
    id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    name VARCHAR(50) NOT NULL DEFAULT ""
);

INSERT INTO reserved(name)
VALUE('hype');

INSERT INTO reserved(name)
VALUE('mods');

INSERT INTO reserved(name)
VALUE('addcommand');