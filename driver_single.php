<?php
/**
 * driver_single.php
 * 
 * Runs a single instance of pheobot connected to a single channel.
 * 
 * @author Brian M. Lenau
 * @version 0.01
 */
use IRC\Bot as Pheobot;

require("classes/irc/bot.php");
require("config/config.php");

$bot = new Pheobot();
$bot->set_log_type("both");
$bot->set_log_dir($config['log_dir']);
$bot->set_password($config['password']);
$bot->set_channel($config['channel']);
$bot->set_controller_file($config['controller_file']);
$bot->connect();
?>