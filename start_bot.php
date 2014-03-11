<?php
/**
 * start_bot.php
 * 
 * Starts the actual bot process.
 * 
 * @author Brian M. Lenau
 * @version 0.01
 */
use IRC\Bot as Pheobot;

require("classes/irc/bot.php");
require("config/config_general.php");

if (isset($argv[1])) {
    require($argv[1]);
    $config['channel'] = $channel;
    $config['log_type'] = $log_type;
}

$bot = new Pheobot();
$bot->set_log_dir($config['log_dir']);
$bot->set_log_type($config['log_type']);
$bot->set_password($config['password']);
$bot->set_controller_file(__DIR__ . "/config/config_mysql.php");
$bot->set_channel($config['channel']);
$bot->connect();
?>