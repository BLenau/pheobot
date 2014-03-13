-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
DELIMITER $$

CREATE DEFINER=`blenau`@`localhost` PROCEDURE `get_all_commands`()
BEGIN
	SELECT name FROM commands;
END 