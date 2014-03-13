<?php
/**
 * mysql.php
 * 
 * This file contains the implementation of the database controller to be used
 * with a MySQL database.
 * 
 * @author Brian M. Lenau
 * @version 0.01
 */
namespace IRC\DB;

require('database.php');

/**
 * A MySQL controller that is used to connect to a database.
 * 
 * @author Brian M. Lenau
 */
class MySQLController implements DatabaseController {
	
	/**
	 * The connection to the database system.
	 * 
	 * @var mixed
	 */
	private $connection;
	
	/**
	 * The server location of the database.
	 * 
	 * @var string
	 */
	private $host;
	
	/**
	 * The user account to connect to the database with.
	 * 
	 * @var string
	 */
	private $user;
	
	/**
	 * The password for the user account.
	 * 
	 * @var string
	 */
	private $pass;
	
	/**
	 * The database to connect to initially.
	 * 
	 * @var string
	 */
	private $db;

	/**
	 * Connect to the database.
	 */
	public function connect() {
		$this->connection = new mysqli($this->host, $this->user, $this->pass, $this->db);
	}
	
	/**
	 * Disconnects from a database.
	 */
	public function disconnect() {
		$this->connection->close();
	}
	
	/**
	 * Run queries on a database.
	 * 
	 * @param string $query The query to be executed on the database.
	 * 
	 * @return array An associative array of the results
	 */
	public function query($query) {
		$results = $this->connection->query($query);
		
		$ret = array();
		if ($results) {
			while ($row = $results->fetch_assoc()) {
				$ret[] = $row;
			}
			$results->close();
			$this->connection->next_result();
		} else {
			$ret = null;
		}
		return $ret;
	}
	
	/**
	 * Calls a stored procedure on the database.
	 * 
	 * @param string $name The name of the tored procedure to call
	 * @param array $params An array of the parameters to pass to the stored procedure.
	 * 
	 * @return array An associative array of the results
	 */
	public function proc($name, $params = "") {
	    $query = "CALL $name(";
	    $comma = "";
	    if (!empty($params)) {
            foreach ($params as $param) {
                if (is_string($param)) {
                    $param = "$comma '$param'";
                } else {
                    $param = "$comma $param";
                }
                $comma = ",";
            }
	    }
	    $query .= ")";
	    
	    return $this->query($query);
	}
	
	
	/**
	 * Sets the host location for the database.
	 * 
	 * @param string
	 */
	public function set_host($host) {
	    $this->host = $host;
	}
	
	/**
	 * Sets the user location for the database.
	 * 
	 * @param string
	 */
	public function set_user($user) {
	    $this->user = $user;
	}
	
	/**
	 * Sets the pass location for the database.
	 * 
	 * @param string
	 */
	public function set_pass($pass) {
	    $this->pass = $pass;
	}
	
	/**
	 * Sets the db location for the database.
	 * 
	 * @param string
	 */
	public function set_db($db) {
	    $this->db = $db;
	}
}
?>