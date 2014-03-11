-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
DELIMITER $$

CREATE DEFINER=`blenau`@`localhost` PROCEDURE `retrieve_command`(
IN com_name VARCHAR(50)
)
BEGIN
	SELECT c.command as command, m.mode as mode FROM commands as c
	LEFT JOIN modes as m
	ON c.mode = m.mode
	WHERE name = com_name;
END 