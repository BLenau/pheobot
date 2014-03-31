-- Creates the table used to store the commands for the bot.
-- Brian M. Lenau
-- 2014/03/10
USE `pheobot`;

DROP TABLE IF EXISTS commands;
CREATE TABLE commands (
    id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    name VARCHAR(50) NOT NULL DEFAULT "",
    channel VARCHAR(50) NOT NULL DEFAULT "",
    mode INTEGER NOT NULL DEFAULT -1,
    command TEXT NOT NULL DEFAULT ""
);