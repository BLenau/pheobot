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
namespace Common\IRC;

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
    private $nick = "Pheobot";
    
    /**
     * The directory where log files will be stored.
     * 
     * @var string
     */
    private $log_dir = "../../../log/";
    
    /**
     * The prefix that is used to define that a command is being invoked.
     * 
     * @var string
     */
    private $command_prefix = "!";
    
    /**
     * The file pointer to the log file.
     * 
     * @var file pointer
     */
    private $log_fp;
    
    /**
     * Creates a new Bot.
     * 
     * @param array $config An array containing the configuration data for the
     *                      new bot.
     */
    public function __construct($config = array()) {
        if (count($config) === 0) {
            return;
        }
        $this->configure($config);
    }
    
    /**
     * Cleans up data associated with the bot when it is destroyed.
     */
    public function __destruct() {
        if ($this->log_fp) {
            fclose($this->log_fp);
        }
    }
    
    /**
     * Connects the bot to the server.
     */
    public function connect() {
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
    public function send($command) {
    }
    
    /**
     * Logs the data using the desired method of logging.
     * 
     * @param string $log The information to log
     * @param string $status The status to prefix to the log
     */
    public function log($log, $status = '') {
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
        $this->connection->set_port($port);
    }
    
    /**
     * Configures the bot with the given configuration array.
     * 
     * @param array $config The array containing the configu
     */
    private function configure($config) {
    }
    
    /**
     * Joins one or more channels.
     * 
     * @param mixed $channel The channel name of the channel to join
     *                       or an array of channel names to join
     */
    private function join($channel) {
    }
}
?>