<?php
/**
 * socket.php
 * 
 * A file containing the connection definition using sockets.
 * 
 * @author Brian M. Lenau
 * @version 0.01
 */
namespace IRC\Connection;

require("connection.php");

/**
 * A class that uses sockets to connect and interact with an IRC server.
 *
 * @author Brian M. Lenau
 */
class Socket implements Connection {
	
	/**
	 * The address of the server that will be connected to.
	 * 
	 * @var string
	 */
	private $server = 'irc.twitch.tv';
	
	/**
	 * The port that will be used to connect to the server.
	 * 
	 * @var int
	 */
	private $port = 6667;
	
	/**
	 * The socket that will connect to the server and will be used to send and
	 * receive data.
	 * 
	 * @param resource
	 */
	private $socket = null;
	
	/**
	 * Performs cleanup on the socket when it is destroyed.
	 */
	public function __destruct() {
		$this->disconnect();
	}
	
	/**
	 * Connect to a server.
	 */
	public function connect() {
		$this->socket = fsockopen($this->server, $this->port);
		if (!$this->connected()) {
			throw new Exception("Unable to connect to the ther server {$this->server}:{$this->port} using fsockopen");
		}
    }
	
	/**
	 * Disconnect from a server.
	 */
	public function disconnect() {
		if ($this->socket) {
			fclose($this->socket);
		}
	}

	/**
	 * Send data to the server.
	 * 
	 * @param string $data The data that will be sent to the server
	 * 
	 * @return boolean FALSE if an error occurs
	 */
	public function send($data) {
		$data .= "\r\n";
		return fwrite($this->socket, $data);
	}
	
	/**
	 * Read data from the server.
	 * 
	 * @return string|boolean The data string received from the server or
	 * 						  FALSE if an error occurred or no data was available
	 */
	public function receive() {
		return fgets($this->socket, 1024);
	}
	
	/**
	 * Checks the status of the connection.
	 * 
	 * @return boolean TRUE if a connection exists, FALSE if it does not.
	 */
	public function connected() {
		return is_resource($this->socket);
	}
	
	/**
	 * Sets the server that will be connected to.
	 * 
	 * @param string $server The server name that will be connected to.
	 */
	public function set_server($server) {
		$this->disconnect();
		$this->server = $server;
	}
	
	/**
	 * Sets the port that will be connected to on the server.
	 * 
	 * @param int $port The port that will be connected to on the server.
	 */
	public function set_port($port) {
		$this->disconnect();
		$this->port = $port;
	}
}

?>