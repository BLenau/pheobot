<?php
/**
 * This file contains the interface responsible for interacting with a chat
 * server using sockets.
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
 * A class that uses sockets to connect and interact with an IRC server.
 *
 * @author Brian M. Lenau
 */
class SocketConnection implements Connection {
	
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
	 * @return mixed A response from the server
	 *               FALSE if an error occurs
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