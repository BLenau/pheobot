<?php
/**
 * connection.php
 * 
 * This file contains the conenction interface that will be used as a template
 * for different connection types.
 * 
 * @author Brian M. Lenau
 * @version 0.01
 */
namespace IRC\Connection;

/**
 * An interface that will be used to connect to IRC servers.
 * 
 * @author Brian M. Lenau
 */
interface Connection {
	
	/**
	 * Connect to a server.
	 */
	public function connect();
	
	/**
	 * Disconnect from a server.
	 */
	public function disconnect();

	/**
	 * Send data to the server.
	 * 
	 * @param string $data The data that will be sent to the server
	 * 
	 * @return boolean FALSE if an error occurs
	 */
	public function send($data);
	
	/**
	 * Read data from the server.
	 * 
	 * @return string|boolean The data string received from the server or
	 * 						  FALSE if an error occurred or no data was available
	 */
	public function receive();
	
	/**
	 * Checks the status of the connection.
	 * 
	 * @return boolean TRUE if a connection exists, FALSE if it does not.
	 */
	public function connected();
}
?>