<?php
/**
 * driver.php
 * 
 * The driver that runs Pheobot.
 */
use IRC\Bot as Pheobot;

require("classes/Common/IRC/bot.php");

$bot = new Pheobot();
$bot->set_log_dir(__DIR__ . "/log/");
$bot->send('TESTING');
?>