<?php
/**
 * This file contains the class definition of the boss.
 * 
 * Copyright (C) 2014 Brian M. Lenau
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @license http://www.gnu.org/licenses/
 * 
 * @author Brian M. Lenau <blenau@gmail.com>
 */
namespace IRC;

/**
 * An IRC bot that is used to connect to Twitch chat (other chats to be 
 * implemented later).  This bot should be able to read the chat and react
 * to the things said there.
 */
class Pheobot {
    
    /**
     * A list of the channels that the bot should be connected to.
     * 
     * @var array
     */
    private $channel = "";
    
    /**
     * The name of the bot. Defaults to Pheobot.
     * 
     * @var string
     */
    private $name = "Pheobot";
    
    /**
     * The nickname of the bot.
     * 
     * @var string
     */
    private $nickname = "Pheobot";

    /**
     * The password to connect to the server
     * 
     * @var string
     */
    private $password = "";
    
    /**
     * The maximum number of reconnects before the bot will exit.
     * 0 for unlimited
     * 
     * @var int
     */
    private $max_reconnects = 0;
    
    /**
     * The manager that is responsible for all commands.
     * 
     * @var \IRC\Commands\Manager
     */
    private $command_manager;

    /**
     * The prefix that is used to define that a command is being invoked.
     * 
     * @var string
     */
    private $command_prefix = "!";
    
    /**
     * The connection used to send and receive data from the server.
     * 
     * @var \IRC\Connection\Connection
     */
    private $connection;
    
    /**
     * The directory where log files will be stored.
     * 
     * @var string
     */
    private $log_dir = "log/";

    /**
     * The directory where log files will be stored.
     * 
     * @var string
     */
    private $log_file = "pheobot.log";
    
    /**
     * An array of file pointers used for logging.
     * 
     * @var array 
     */
    private $log_fp;
    
    /**
     * The owner of the chat channel.
     * 
     * @var string
     */
    private $owner;
    
    /**
     * An array containing the mods for the channel.
     * 
     * @var array
     */
    private $mods = array();
    
    /**
     * The method used for logging.
     * Valid values:
     *     screen - Writes log to stdout
     *     file   - Writes log to a logfile
     *     both   - Writes log to both stdout and a logfile
     * 
     */
    private $log_type = "file";
    
    /**
     * The time stamp of the last message sent by the bot.
     * This is to make sure that the bot does not send too many messages.
     * 
     * @var string
     */
    private $last_com = "0";
    
    /**
     * Creates a new Bot.
     * 
     * @param array $config An array containing the configuration data for the
     *                      new bot.
     */
    public function __construct($config = array()) {
    	$this->open_logs();
    	$this->command_manager = new \IRC\Command\Manager();
    	$this->connection = new \IRC\Connection\SocketConnection();
    }
    
    /**
     * Cleans up data associated with the bot when it is destroyed.
     */
    public function __destruct() {
    	$this->close_logs();
    }
    
    /**
     * Connects the bot to the server.
     */
    public function connect() {
    	if ($this->connection->connected()) {
    		$this->connection->disconnect();
    	}
    	$this->log("Connecting to server...");
    	$this->connection->connect();
    	$this->send("USER $this->nickname");
    	$this->send("PASS $this->password");
    	$this->send("NICK $this->nickname");
    	
    	$this->do_work();
    }
    
    /**
     * Disconnects the bot from the server.
     */
    public function disconnect() {
        $this->connection->disconnect();
    }
    
    /**
     * Sends data to the server.
     * 
     * @param string $command The command to send to the server.
     */
    public function send($data) {
    	$this->log($data, 'SEND');
    	$this->connection->send($data);
    }
    
    /**
     * Sends a message to the chat.  This method differs from send() by
     * prepending the "PRIVMSG #CHANNEL_NAME :" to the data.
     * 
     * @param string $message The message to send to the chat
     */
    public function send_message($data) {
        $this->send("PRIVMSG #$this->channel :$data");
    }
    
    /**
     * Logs the data using the desired method of logging.
     * 
     * @param string $log The information to log
     * @param string $status The status to prefix to the log
     */
    public function log($log, $status = '') {
    	if (empty($status)) {
    		$status = "LOG";
    	}
    	$log = str_replace(array(chr(10), chr(13)), '', $log);
    	$log .= "\r\n";
    	$now = date("Y-m-d H:i:s");
    	$status = "$now [$status]";
    	while (strlen($status) < 6) {
    		$status .= " ";
    	}
    	
    	$log = "$status $log";
    	
    	foreach ($this->log_fp as $fp) {
    		fwrite($fp, $log);
    	}
    }
    /**
     * Opens the log files for logging.
     */
    private function open_logs() {
        $this->log_fp = array();
        if ($this->log_type == "screen" || $this->log_type == "both") {
            $this->log_fp[] = fopen("php://stdout", "w");
        }
        if ($this->log_type == "file" || $this->log_type == "both") {
            $this->log_fp[] = fopen("{$this->log_dir}{$this->log_file}", "a");
        }
    }

    /**
     * Close any files that are currently open for logging.
     */
    private function close_logs() {
    	foreach ($this->log_fp as $fp) {
    		if ($fp) {
    			fclose($fp);
    		}
    	}
    }
    
    /**
     * Creates a new database controller.
     */
    private function create_controller() {
        require($this->controller_file);

        $l_type = strtolower($this->controller_type);
        require("db/$l_type.php");

        $type = "\IRC\DB\{$this->controller_type}";
        $this->controller = new $type;
        $this->controller->set_host($host);
        $this->controller->set_user($user);
        $this->controller->set_pass($pass);
        $this->controller->set_db($db);
    }
    
    /**
     * Joins one or more channels.
     * 
     * @param mixed $channel The channel name of the channel to join
     *                       or an array of channel names to join
     */
    private function join($channel) {
        $this->send("JOIN #$channel");
    }
    
    /**
     * The workhorse function of the class.  Contains the loop that does all the work.
     */
    private function do_work() {
    	$go = true;
    	while ($go) {
    		$data = $this->connection->receive();
    		$this->log($data, 'RECV');
    		
    		$args = explode(' ', $data);
    		if ($args[0] == 'PING') {
    			$this->send("PONG {$args[1]}");
    		}

    		if (isset($args[1])) {
                if (intval($args[1]) == 376) {
                    $this->join($this->channel);
                }
                if ($args[1] == "MODE") {
                	$this->set_mode($data);
                }
    			if ($args[1] == "PRIVMSG") {
    			    $tokens = explode("!", $args[0]);
    			    $user = trim($tokens[0]);
    			    $user = substr($user, 1);

                    $command = trim($args[3]) . " " . $user . " " . $this->channel;
    			    for ($i = 4; $i < count($args); $i++) {
    			        $command .= " " . trim($args[$i]);
                    }
    			    $command = substr($command, 1);
    			    if (stripos($command, $this->command_prefix) === 0) {
    			        $this->update_roles();
    			        if (in_array($user, $this->mods)) {
                            $result = $this->command_manager->execute($command);
                            if ($result) {
                                $this->send_message($result);
                            }
    			        }
    			    }
    			}
    		}
    	}
    }
    
    /**
     * Sets the user modes for the chat.
     * 
     * @param string $line The line that was read in containing user mode data
     */
    private function set_mode($line) {
    	$args = explode(' ', $line);
    	$mode = $args[3];
    	switch ($mode) {
    		case "+o":
    			$this->owner = $args[4];
    			break;
    	}
    }
    
    /**
     * Updates the roles of all the users on the chat.
     */
    private function update_roles() {
        @$json = file_get_contents("https://tmi.twitch.tv/group/user/{$this->channel}/chatters");
        if ($json) {
            $chatters = json_decode($json, true);
            $this->mods = array();
            foreach ($chatters['chatters']['moderators'] as $mod) {
                $this->mods[] = $mod;
            }
        }
    }

    /**
     * Displays a list of moderators.
     * 
     * @param string $cmd The name of the command that is being executed
     * @param array $params The array of parameters retrieved from the database
     * $params -
     *     mod => The permission level to display
     *         - moderators
     *         - staff
     *         - admins
     *         - viewers
     *         - chatter_count
     * @param array $data The array of data retrieved from the database
     * $data -
     *     last_count - The user count the last time this command was invoked.
     */
    private function chat_list($cmd, $params, $data) {
    	$mod = $params['mod'];
    	@$json = file_get_contents("https://tmi.twitch.tv/group/user/$channel/chatters");
    	if ($json) {
    		$chatters = json_decode($json, true);
    		if ($mod == 'chatter_count') {
    			$count = $chatters[$mod];
    			$last_count = $data['last_count'];
    			$diff = $count - $last_count;
    			$output = "Viewer count   => $count "
    				    . "Previous count => $last_count "
    				    . "Change         => $diff      ";
    			$this->send_message($output);
    			$data['last_count'] = $count;
    			$data = $json_encode($data);
    			$query = "CALL update_command_data('$data')";
    			$this->controller->query($query);
    		}
    		$list = $chatters['chatters'][$mod];
    		$pretty_mod = ucfirst($mod);
    		$output = "The online $pretty_mod are:";
    		$comma = "";
    		for ($i = 0; $i < count($list); $i++) {
    		    $output .= "$comma {$list[$i]}";
    		    $comma = ",";
    		}
    	}
    }
    
    
    /** ------------------------------------------------------------------- **
     * Setters
     ** ------------------------------------------------------------------- **/
    
    /**
     * Sets the server.
     * 
     * @param string $server The server to connect the bot to
     */
    public function set_server($server) {
        $this->connection->set_server($server);
    }
    
    /**
     * Sets the port.
     * 
     * @param int $port The port to connect the bot to
     */
    public function set_port($port) {
        $this->connection->set_port($port);
    }

    /**
     * Sets the channel(s)
     * 
     * @param mixed $channel A single channel name or an array of channel name
     *                       to connect to
     */
    public function set_channel($channel) {
        $this->channel = $channel;
        $this->owner = $channel;
        $this->set_log_file("{$this->nickname}.{$this->channel}.log");
    }

    /**
     * Sets the nickname of the bot.
     * 
     * @param string $nickname The nickname of the bot
     */
    public function set_nickname($nickname) {
    	$this->nickname = (string) $nickname;
        $this->set_log_file("{$this->nickname}.{$this->channel}.log");
    }

    /**
     * Sets the password of the bot.
     * 
     * @param string $password The password of the bot
     */
    public function set_password($password) {
    	$this->password = (string) $password;
    }
    
    /**
     * Sets the max number of reconnects before the bot will exit.
     * 
     * @param int $reconnects The number of reconnects before the bot will exit
     */
    public function set_max_reconnects($reconnects) {
    	$this->max_reconnects = (int) $reconnects;
    }
    
    /**
     * Sets the log file.
     * 
     * @param string $log_file The file that logs will be written to
     */
    public function set_log_file($log_file) {
    	$this->close_logs();
    	$this->log_file = (string) $log_file;
    	$this->open_logs();
    }
    
    /**
     * Sets the log type.
     * 
     * @param string $log_type The type of logging to use.
     */
    public function set_log_type($log_type) {
    	$this->close_logs();
    	$this->log_type = (string) $log_type;
    	$this->open_logs();
    }
    
    /**
     * Sets the log directory.
     * 
     * @param string $log_dir The directory log files will be saved in
     */
    public function set_log_dir($log_dir) {
    	$this->close_logs();
    	$this->log_dir = (string) $log_dir;
    	$this->open_logs();
    }
    
    /**
     * Sets the controller configuation file.
     * 
     * @param string 
     */
    public function set_controller_file($config_file) {
        $this->controller_file = $config_file;
        $this->create_controller();
    }
    
    /**
     * Sets the controller type to use.
     * 
     * @param string
     */
    public function set_controller_type($type) {
        $l_type = strtolower($type);
        if (file_exists("db/$l_type.php")) {
            $this->controller_type = $type;
            $this->create_controller();
        }
    }
}
?>