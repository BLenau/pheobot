<?php
/**
 * handler.php
 * 
 * This file contains a class that handles commands retrieved from a database.
 * 
 * @author Brian M. Lenau
 * @version 0.01
 */
namespace IRC\Command;

require('handler.php');

/**
 * An interface that is responsible for handling the command execution.
 * 
 * @author Brian M. Lenau
 */
class DatabaseHandler implements Handler {
    
    /**
     * The database controller used to interact with the database.
     * 
     * @var \IRC\Connection\Connection
     */
    private $controller;
    
    /**
     * The type of database connection to use.
     * Options - 
     *     MySQL
     * 
     * @var string
     */
    private $controller_type = "MySQL";
    
    /**
     * The controller configuration file.
     * 
     * @var string
     */
    private $controller_file = "./../../config/config_mysql.php";
    
    /**
     * A list of commands that are currently available.
     * 
     * @var array
     */
    private $commands = array();
    
    /**
     * The constructor of the class that will initialize the variables.
     */
    public function __construct() {
        $type = "IRC\Connection\{$this->connection_type}";
        $connection = new $type;
        
        $commands = array();
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
     * Execute a command.
     * 
     * @param string $command The name of the command to execute
     */
    public function execute($command) {
        $this->get_commands();
        $this->process_command($line);
    }
    
    /**
     * Update the list of the currently available commands.
     */
    public function get_commands() {
        $proc = "get_all_commands";
        $results = $this->controller->proc($proc);
        if ($results) {
            $this->commands = array();
            foreach ($results as $result) {
                $this->commands[] = $result['name'];
            }
        }
        
        return $this->commands;
    }
    
    /**
     * Processes a command that has been sent to the bot.
     * 
     * @param string $line The line that was read in containing the command
     */
    private function process_command($line) {
    	$args = explode(' ', $line);
    	$command = "";
        for ($i = 3; $i < count($args); $i++) {
            $command .= $args[$i];
        }
        $command = substr($command, 1);
        if (stripos($command, '!') === 0) {
            $user = explode('!', $args[0]);
            $user = substr($user[0], 1);
            $com_args = explode(' ', $command);
            $cmd = $com_args[0];
            $this->update_roles();
            $run = $this->check_command($cmd, $user);
            $data = $this->run_command($run);
            if ($data) {
                $this->update_command($cmd, $data);
            }
        }
    }
    
    /**
     * Checks that a command exists and that the user has the permission to
     * call it.
     * 
     * @param string $command The command that is being checked
     * @param string $user The user who invoked the command
     * 
     * @return string|boolean The command to execute if the permissions check
     *                        out or false if they don't
     */
    private function check_command($command, $user) {
        $query = "CALL retrieve_command('$command')";
        $proc = "retrieve_command";
        $results = $controller->query($query);
        
        $run = false;
        if (count($results) > 0) {
            $mode = $results['mode'];
            if ($user == $this->owner) {
                $run = results;
            } else {
                if ($mode == 'mod') {
                    if (in_array($user, $this->mods)) {
                        $run = $results;
                    }
                } else if ($mode == 'all') {
                    $run = $results;
                }
            }
        }
        return $run;
    }
    
    /**
     * Runs a given command.
     * 
     * @param string $command The command to be run
     */
    private function run_command($command) {
        $command = json_decode($command, true);
        $action = $command['action'];
        $params = $command['params'];
        $data = $command['data'];
        
        if (method_exists($action)) {
        	$ret = $action($params, $data);
        }
        
        return $ret;
    }
    
    /**
     * Adds a command to the list of commands in the database.
     * 
     * @param string $name The name of the command to add
     * @param string $command The command that will be executed when the new
     * 						  command is invoked.
     */
    private function add_command($name, $command) {
    	$query = "CALL add_command('$name', '$command')";
    	$this->controller->query($query);
    }

    /**
     * Sets the controller configuation file.
     * 
     * @param string 
     */
    public function set_controller_file($config_file) {
        $this->controller_file = $config_file;
        create_controller();
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
            create_controller();
        }
    }
}
?>