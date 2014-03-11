-- Creates the table used to store the modes that commands can have.
-- Brian M. Lenau
-- 2014/03/10
USE `pheobot`;

DROP TABLE IF EXISTS modes;
CREATE TABLE modes (
    id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    mode VARCHAR(50) NOT NULL DEFAULT "",
    code INTEGER NOT NULL DEFAULT 0,
);