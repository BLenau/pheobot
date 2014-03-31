-- Creates the table used to store the modes that commands can have.
-- Brian M. Lenau
-- 2014/03/10
USE `pheobot`;

DROP TABLE IF EXISTS modes;
CREATE TABLE modes (
    id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    mode VARCHAR(50) NOT NULL DEFAULT "",
    code INTEGER NOT NULL DEFAULT 0
);

INSERT INTO modes (mode, code)
VALUES ("Owner", 0);

INSERT INTO modes (mode, code)
VALUES ("Channel Owner", 1);

INSERT INTO modes (mode, code)
VALUES ("Moderator", 2);

INSERT INTO modes (mode, code)
VALUES ("All", 3);