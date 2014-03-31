-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
DELIMITER $$

USE `pheobot`;

DROP PROCEDURE IF EXISTS `add_global`;
CREATE DEFINER=`blenau`@`localhost` PROCEDURE `add_global`(
IN global_name VARCHAR(50),
IN global_value TEXT,
IN this_channel VARCHAR(50)
)
BEGIN
	INSERT INTO globals (name, val, channel)
	VALUES (global_name, global_value, this_channel);
END 