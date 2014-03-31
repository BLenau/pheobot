<?php
/**
 * This file contains the interface responsible for interacting with a chat
 * server.
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
	 * Checks the status of the connection.
	 * 
	 * @return boolean TRUE if a connection exists, FALSE if it does not.
	 */
	public function connected();

	/**
	 * Send data to the server.
	 * 
	 * @param string $data The data that will be sent to the server
	 * 
	 * @return mixed A response from the server
	 *               FALSE if an error occurs
	 */
	public function send($data);
	
	/**
	 * Read data from the server.
	 * 
	 * @return string|boolean The data string received from the server or
	 * 						  FALSE if an error occurred or no data was available
	 */
	public function receive();
}
?>