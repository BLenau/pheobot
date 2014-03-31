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
class GlobalHandler implements \IRC\Command\Handler {
    
    /**
     * A list of commands that are currently available.
     * 
     * @var array
     */
    private $commands;
    
    /**
     * A list of methods to ignore when listing the commands.
     * 
     * @var array
     */
    private $ignore = array("connect", "disconnect", "execute", "get_commands");
    
    /**
     * The constructor of the class that will initialize the variables.
     */
    public function __construct() {
        $this->commands = array();
    }
    
    /**
     * Cleans up any data associated with the handler.
     */
    public function __destruct() {
        $this->commands = null;
    }

    /**
     * Connects the handler to any sources that it will use.
     */
    public function connect() {
        get_commands();
    }
    
    /**
     * Connects the handler to any sources that it will use.
     */
    public function disconnect() {
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
     */
    public function execute($command) {
        $this->get_commands();
        $tokens = explode(" ", $command);
        $command = trim($tokens[0]);
        $command = substr($command, 1);
        
        $args = array();
        for ($i = 1; $i < count($tokens); $i++) {
            $args[] = trim($tokens[$i]);
        }
        if (in_array($command, $this->commands)) {
            return $this->$command($args);
        }
        
        return false;
        //return "I don't know how to do that yet. OpieOP";
    }
    
    /**
     * Update the list of the currently available commands.
     * 
     * @return array The list of currently available commands
     */
    public function get_commands() {
        $commands = get_class_methods($this);
        foreach ($commands as $command) {
            if (!in_array($command, $this->ignore)) {
                $this->commands[] = (string) $command;
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
        return "GlobalHandler";
    }
    
    private function hype($args) {
        return "HYPE! TriHard";
    }
    
    private function mods($args) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://tmi.twitch.tv/group/user/{$args[1]}/chatters");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        
        $results = curl_exec($ch);
        
        curl_close($ch);

        if ($results) {
            $json = json_decode($results, true);
            $mods = "";
            $space = "";
            foreach ($json['chatters']['moderators'] as $mod) {
                $mods .= $space . $mod;
                $space = " ";
            }
            return "Currently online mods: $mods KevinTurtle";
        }
    }
}
?>