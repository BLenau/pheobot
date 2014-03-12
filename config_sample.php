
<?php
$config = array();
// Channel to connect to
$config['channel'] = "pheogia";

// The file that contains the database controller information
$config['controller_file'] = __DIR__ . "/sample_config_mysql.php";

// The directory where log files will be stored
$config['log_dir'] = __DIR__ . "/../log/";

// The type of logging to use
$config['log_type'] = "both";

// The name/nickname of the bot.
// This must be the username for the twitch account that will act as the bot
$config['name'];

// The oauth password token used to log into the Twitch IRC
// Can be found at http://twitchapps.com/tmi/ while logged into the account that will be your bot
$config['password'] = "oauth:SomePasswordString";
?>