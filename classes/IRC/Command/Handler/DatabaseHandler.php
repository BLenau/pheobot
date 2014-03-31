<?php
/**
 * This file contains the class responsible for handling database commands.
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
namespace IRC\Command\Handler;

/**
 * An interface that is responsible for handling the command execution.
 * 
 * @author Brian M. Lenau
 */
class DatabaseHandler implements \IRC\Command\Handler {
    
    /**
     * The database controller used to interact with the database.
     * 
     * @var \IRC\Connection\Connection
     */
    private $connection;
    
    /**
     * The controller configuration file.
     * 
     * @var string
     */
    private $config;
    
    /**
     * A list of commands that are currently available.
     * 
     * @var array
     */
    private $commands;
    
    /**
     * The channel that this handler belongs to.
     * 
     * @var string
     */
    private $channel;
    
    /**
     * The constructor of the class that will initialize the variables.
     */
    public function __construct() {
        $this->connection = new \IRC\Connection\DatabaseConnection();
        $this->config = ROOT . "/config/config_db.php";
        $this->commands = null;

    }
    
    /**
     * Cleans up any data associated with the handler.
     */
    public function __destruct() {
        if ($this->connection->connected()) {
            $this->connection->disconnect();
        }
        $this->connection = null;
        $this->config = null;
        $this->commands = null;
    }
    
    /**
     * Connects the handler to any sources that it will use.
     */
    public function connect() {
        if (file_exists($this->config)) {
            require($this->config);
            $this->connection->set_connection_string($config['connection_string']);
            $this->connection->set_user($config['user']);
            $this->connection->set_pass($config['pass']);
        }
    	if ($this->connection->connected()) {
    		$this->connection->disconnect();
    	}
    	$this->connection->connect();
    }
    
    /**
     * Connects the handler to any sources that it will use.
     */
    public function disconnect() {
    	if ($this->connection->connected()) {
    		$this->connection->disconnect();
    	}
    }

    /**
     * Execute a command.
     * 
     * @param string $command The command to execute
     * format: [COMMAND_PREFIX]command arg0 arg1 arg2 ...
     * The command will always have the command prefix prepended to it
     * The first argument (arg0) is always the user who invoked the command
     * The second argument (arg1) is always the channel
     * 
     * @return string The data to send to the server
     *                FALSE if the command was not found
     */
    public function execute($command) {
        $tokens = explode(" ", $command);
        $command = trim($tokens[0]);
        $command = substr($command, 1);
        
        $args = array();
        for ($i = 1; $i < count($tokens); $i++) {
            $args[] = trim($tokens[$i]);
        }
        if ($this->commands == null) {
            $this->channel = $args[1];
            $this->get_commands();
        }
        if ($command == "addcommand") {
            return add_command($args);
        } else if ($command == "addglobal") {
            return add_global($args);
        } else if (in_array($command, $this->commands)) {
        }
        return false;
        /*
        $this->process_command($line);
        */
    }
    
    /**
     * Update the list of the currently available commands.
     * 
     * @return array The list of currently available commands
     */
    public function get_commands() {
        $query = "CALL get_all_commands('{$this->channel}')";
        $results = $this->connection->query($query);
        if ($results) {
            $this->commands = array();
            foreach ($results as $result) {
                $this->commands[] = $result['name'];
            }
        }
        
        return $this->commands;
    }

    /**
     * Returns the type of the handler that this is (The name of the class).
     * 
     * @return string The type of handler that this is
     */
    public function type() {
        return "DatabaseHandler";
    }
    
    /**
     * Adds a new command to the database.
     * 
     * @param array $args The array of arguments for the command
     * 
     * @return mixed The string of data to send to the server
     *               FALSE on failure
     */
    private function add_command($args) {
        $user = $args[0];
        $channel = $args[1];
        $name = $args[2];
        
        $command = array();
        $command['params'] = array();
        $command['command'] = array();
        for ($i = 3; $i < count($args) && stripos("-p", $args[$i]) === 0; $i++) {
        }
    }
    
    /**
     * Add a new global to the database.
     * 
     * TODO: Figure out how to figure out user permissions here
     * 
     * @param array $args The array of arguments for the command
     * 
     * @return mixed The string of data to send to the server
     *               FALSE on failure
     */
    private function add_global($args) {
        $user = $args[0];
        $channel = $args[1];
        $name = $args[2];
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
     * Sets the controller configuation file.
     * 
     * @param string 
     */
    public function set_config_file($config_file) {
        if (!$this->connection->connected) {
            $this->config = $config_file;
        }
    }
}
?>