-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
DELIMITER $$

USE `pheobot`;

DROP PROCEDURE IF EXISTS `fetch_global`;
CREATE DEFINER=`blenau`@`localhost` PROCEDURE `fetch_global`(
IN global_name VARCHAR(50),
IN global_channel VARCHAR(50)
)
BEGIN
	SELECT val FROM globals
	WHERE name = global_name
	AND channel = global_channel;
END 