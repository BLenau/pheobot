<?php
/**
 * This file contains an interface used to connect to the database.
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
namespace IRC\Connection;

/**
 * And interface used as a means to interact with a database system.
 * 
 * @author Brian M. Lenau
 */
class DatabaseConnection implements Connection {
    
    /**
     * The connection string to use to connect to the database.
     * SEE: php.net/manual/pdo.construct.php
     * 
     * @var string
     */
    private $connection_string;
    
    
    /**
     * The username to connecto to the database with.
     * 
     * @var string
     */
    private $user;
    
    /**
     * The password to connect to the database with.
     * 
     * @var string
     */
    private $pass;
    
    /**
     * The database object.
     * 
     * @var PDO
     */
    private $db = null;
    
    /**
     * Creates a new database connection.
     */
    public function __construct() {
    }
    
    /**
     * Disassociates any data attached to this connection.
     */
    public function __destruct() {
        $db = null;
    }
	
	/**
	 * Connect to the database.
	 */
	public function connect() {
	    try {
	        $this->db = new PDO($this->connection_string, $this->user, $this->pass);
	    } catch (PDOException $e) {
	        echo "Connection failed: " . $e->getMessage();
	        exit;
	    }
	}
	
	/**
	 * Disconnects from a database.
	 */
	public function disconnect() {
        $db = null;
	}

	/**
	 * Checks the status of the connection.
	 * 
	 * @return boolean TRUE if a connection exists, FALSE if it does not.
	 */
	public function connected() {
	    return $this->db == null;
	}

	/**
	 * Send data to the server.
	 * 
	 * @param string $data The data that will be sent to the server
	 * 
	 * @return mixed A response from the server
	 *               FALSE if an error occurs
	 */
	public function send($data) {
	   return $db->query($data);
	}
	
	/**
	 * Read data from the server.
	 * Any data received from the database will be returned from the
	 * <code>send</code> function.
	 * 
	 * @return string|boolean The data string received from the server or
	 * 						  FALSE if an error occurred or no data was available
	 */
	public function receive() {
	    return true;
	}
	
	/**
	 * Sets the connection string used to connect to the server.
	 * 
	 * @param string $connection_string
	 */
	public function set_connection_string($connection_string) {
	    $this->connection_string = $connection_string;
	}
	
	/**
	 * Sets the user to connect to the server with.
	 * 
	 * @param string $user
	 */
	public function set_user($user) {
	    $this->user = $user;
	}
	
	/**
	 * Sets the pass to connect to the server with.
	 * 
	 * @param string $pass
	 */
	public function set_pass($pass) {
	    $this->pass = $pass;
	}
}
?>