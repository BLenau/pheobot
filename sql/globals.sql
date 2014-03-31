-- Creates the table used to store command globals for the bot.
-- Brian M. Lenau
-- 2014/03/30
USE `pheobot`;

DROP TABLE IF EXISTS globals;
CREATE TABLE globals(
    id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    name VARCHAR(50) NOT NULL DEFAULT "",
    val TEXT NOT NULL DEFAULT "",
    channel VARCHAR(50) NOT NULL DEFAULT ""
);