-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
DELIMITER $$

USE `pheobot`;

DROP PROCEDURE IF EXISTS `fetch_command`;
CREATE DEFINER=`blenau`@`localhost` PROCEDURE `fetch_command`(
IN command_name VARCHAR(50),
IN command_channel VARCHAR(50)
)
BEGIN
	SELECT c.command as command, m.mode as mode FROM commands as c
	LEFT JOIN modes as m
        ON c.mode = m.mode
	WHERE name = command_name
	AND channel = command_channel;
END 