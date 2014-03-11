<?php
/**
 * driver.php
 * 
 * The driver that runs Pheobot.
 */
use IRC\Bot as Pheobot;

require("classes/irc/bot.php");
require("config/config.php");

$bot = new Pheobot();
$bot->set_log_dir($config['log_dir']);
$bot->set_password($config['password']);
$bot->set_channel($config['channel']);
$bot->connect();
?>