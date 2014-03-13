<?php
/**
 * handler.php
 * 
 * This file contains an interface that will act as a command handler.
 * 
 * @author Brian M. Lenau
 * @version 0.01
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
     * Execute a command.
     * 
     * @param string $command The name of the command to execute
     */
    public function execute($command);
}
?>