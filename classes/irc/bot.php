<?php
/**
 * pheobot.php
 * 
 * This file contains the workhorse class for the Pheobot.  This class is
 * respinsible for reacting to the data that the retrieved from the server.
 * 
 * @author Brian M. Lenau
 * @version 0.01
 */
namespace IRC;

/**
 * An IRC bot that is used to connect to Twitch chat (other chats to be 
 * implemented later).  This bot should be able to read the chat and react
 * to the things said there.
 */
class Bot {
    
    /**
     * The connection that is used to connect to the server.
     * 
     * @var \Common\Connection
     */
    private $connection;
    
    /**
     * A list of the channels that the bot should be connected to.
     * 
     * @var array
     */
    private $channel = array();
    
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
     * The prefix that is used to define that a command is being invoked.
     * 
     * @var string
     */
    private $command_prefix = "!";
    
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
     * The method used for logging.
     * Valid values:
     *     screen - Writes log to stdout
     *     file   - Writes log to a logfile
     *     both   - Writes log to both stdout and a logfile
     * 
     */
    private $log_type = "both";
    
    /**
     * Creates a new Bot.
     * 
     * @param array $config An array containing the configuration data for the
     *                      new bot.
     */
    public function __construct($config = array()) {
    	$this->open_logs();
    	$this->connection = new \IRC\Connection\Socket;
        if (count($config) === 0) {
            return;
        }
        $this->configure($config);
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
    }
    
    /**
     * Disconnects the bot from the server.
     */
    public function disconnect() {
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
     * Logs the data using the desired method of logging.
     * 
     * @param string $log The information to log
     * @param string $status The status to prefix to the log
     */
    public function log($log, $status = '') {
    	if (empty($status)) {
    		$status = "LOUT";
    	}
    	$now = date("Y-m-d H:i:s");
    	$status = "$now [$status]";
    	while (strlen($status) < 6) {
    		$status .= " ";
    	}
    	
    	$log = "$status $log\n";
    	
    	foreach ($this->log_fp as $fp) {
    		fwrite($fp, $log);
    	}
    }
    
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
        $this->channel = (array) $channel;
    }

    /**
     * Sets the nickname of the bot.
     * 
     * @param string $nickname The nickname of the bot
     */
    public function set_nickname($nickname) {
    	$this->nickname = (string) $nickname;
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
     * Configures the bot with the given configuration array.
     * 
     * @param array $config The array containing the configu
     */
    private function configure($config) {
    	$this->set_server($config['server']);
    	$this->set_port($config['port']);
    	$this->set_channel($config['channel']);
    	$this->set_name($config['name']);
    	$this->set_nickname($config['nickname']);
    	$this->set_max_reconnects($config['max_reconnects']);
    	$this->set_log_file($config['log_file']);
    	$this->set_log_file($config['log_type']);
    }
    
    /**
     * Joins one or more channels.
     * 
     * @param mixed $channel The channel name of the channel to join
     *                       or an array of channel names to join
     */
    private function join($channel) {
    }
    
    /**
     * The workhorse function of the class.  Contains the loop that does all the work.
     */
    private function do_work() {
    }
}
?>