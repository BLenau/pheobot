-- Creates the table used to store the commands for the bot.
-- Brian M. Lenau
-- 2014/03/10
USE `pheobot`;

DROP TABLE IF EXISTS reserved;
CREATE TABLE  reserved (
    id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    name VARCHAR(50) NOT NULL DEFAULT ""
);