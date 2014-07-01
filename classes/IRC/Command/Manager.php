<?php
/**
 * This file contains the class responsible managing all the command handlers.
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
namespace IRC\Command;

/**
 * An interface that is responsible for handling the command execution.
 * 
 * @author Brian M. Lenau
 */
class Manager {
    
    /**
     * The list of command handler types that are currently available.
     * 
     * @var array
     */
    private $types;
    
    /**
     * The list of command handlers that are currently available.
     * 
     * @var \IRC\Command\Handler
     */
    private $handlers;
    
    /**
     * Initializes the command handlers.
     */
    public function __construct() {
        $this->types = array();
        $this->handlers = array();
        $fp = opendir(__DIR__ . "/Handler/");
        if ($fp) {
            while (($filename = readdir($fp)) !== false) {
                if (strpos($filename, ".php") !== false) {
                    $tokens = explode(".", $filename);
                    $class = "\\IRC\\Command\\Handler\\" . $tokens[0];
                    $handler = new $class();
                    if ($handler->type() == "DatabaseHandler") {
                    } else {
                        $this->handlers[] = new $class();
                    }
                }
            }
        }
    }
    
    /**
     * Cleans up any data associated with the handler.
     */
    public function __destruct() {
        $types = null;
        $handlers = null;
        $commands = null;
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
     * @return string The data to send back to the server
     *                FALSE if command does not exist
     */
    public function execute($command) {
        foreach ($this->handlers as $handler) {
            if (($result = $handler->execute($command)) !== false) {
                return $result;
            }
        }
        return false;
    }
}
?>