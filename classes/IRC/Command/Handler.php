<?php
/**
 * This file contains an interface that will act as a command handler.
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
interface Handler {

    /**
     * Return a list of currently available commands.
     * 
     * @return array The list of currently available commands
     */
    public function get_commands();
    
    /**
     * Connects the handler to any sources that it will use.
     */
    public function connect();

    /**
     * Connects the handler to any sources that it will use.
     */
    public function disconnect();
    
    /**
     * Execute a command.
     * 
     * @param string $command The command to be executed with the arguments
     *                        for it
     * format: [COMMAND_PREFIX]command arg0 arg1 arg2 ...
     * The command will always have the command prefix prepended to it
     * The first argument (arg0) is always the user who invoked the command
     * 
     * @return string The data to send back to the server
     *                FALSE if command does not exist
     */
    public function execute($command);
    
    /**
     * Returns the type of the handler that this is (The name of the class).
     * 
     * @return string The type of handler that this is
     */
    public function type();
}
?>