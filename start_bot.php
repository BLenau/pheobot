<?php
/**
 * start_bot.php
 * 
 * Starts the actual bot process.
 * 
 * @author Brian M. Lenau
 * @version 0.01
 */
define('ROOT', __DIR__);

// Used to autoload classes
require("classes/Autoloader.php");

spl_autoload_register("Autoloader::load");

$config = array();
if (file_exists(ROOT . "/config/config_general.php")) {
    require("config/config_general.php");
}

if (isset($argv[1])) {
    if (file_exists($argv[1])) {
        require($argv[1]);
        $config['channel'] = $channel;
        $config['log_type'] = $log_type;
    }
}

if (isset($argv[1])) {
    require($argv[1]);
    $config['channel'] = $channel;
    $config['log_type'] = $log_type;
}

$bot = new \IRC\Pheobot();
$bot->set_log_dir($config['log_dir']);
$bot->set_log_type($config['log_type']);
$bot->set_password($config['password']);
$bot->set_channel($config['channel']);
$bot->connect();
?>