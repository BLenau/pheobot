<?php
/**
 * database.php
 * 
 * This file contains an interface used to connect to the database.
 * 
 * @author Brian M. Lenau
 * @version 0.01
 */
namespace IRC\DB;

/**
 * And interface used as a means to interact with a database system.
 * 
 * @author Brian M. Lenau
 */
interface DatabaseController {
	
	/**
	 * Connect to the database.
	 */
	public function connect();
	
	/**
	 * Disconnects from a database.
	 */
	public function disconnect();
	
	/**
	 * Run queries on a database.
	 * 
	 * @param string $query The query to be executed on the database.
	 * 
	 * @return array An associative array of the results
	 */
	public function query($query);
	
	/**
	 * Calls a stored procedure on the database.
	 * 
	 * @param string $name The name of the tored procedure to call
	 * @param array $params An array of the parameters to pass to the stored procedure.
	 * 
	 * @return array An associative array of the results
	 */
	public function proc($name, $params);
}
?>