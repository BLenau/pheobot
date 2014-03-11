<?php
/**
 * driver.php
 * 
 * The driver that runs Pheobot.
 */
$config_dir = "config/channels/";
if ($handle = opendit($config_dir)) {
    while (($file = readdir($handle)) !== false) {
        $file = __DIR__ . "config/channels/$file";
        exec("php start_bot.php $file &");
    }
}
?>