<?php
/**
 * Pheobot
 * 
 * @author Brian M. Lenau
 * @version 0.01
 * 
 * A Twitch bot that can do lots of stuff.  Kappa
 * I have no idea what I'm doing.
 */
echo "Starting Pheobot\r\n";
$host = "irc.twitch.tv";
$port = 6667;
$channel = "#Pheogia";
$user = "Pheobot";
$pass = "oauth:m8vico46kvz4shfscx65ypqqkmf1rn2";

set_time_limit(0);

$server = array();

$socket = fsockopen($host, $port); 
if ($socket) {
    send("USER $user", $socket);
    send("PASS $pass", $socket);
    send("NICK $user", $socket);
    send("JOIN $channel", $socket);
    while (1) {
        $message = recv($socket);
        /*
        if (strlen($message) > 0) {
            $tokens = parse_line($message);
            if ($tokens == "pong") {
                $pong = substr($message, 5);
                send("PONG $pong", $socket);
            } else if ($tokens != null) {
                if (intval($tokens['status']) == 376) {
                    // Connected to server, time to join
                    send("JOIN $channel", $socket);
                }
                if ($tokens['status'] == 'PRIVMSG') {
                    send("PRIVMSG :Nobody fear, Pheobot is here", $socket);
                }
            }
        }
        */
    }
}

/**
 * Sends a message to the chat server.
 * 
 * @param string $message The message that will be sent to the server
 * @param mixed $socket A file pointer that acts as a socet to the server
 */
function send($message, $socket) {
    $message .= "\r\n";
    @fwrite($socket, $message, strlen($message));
    echo "[SEND] $message";
}

/**
 * Receives a message from the chat server.
 * 
 * @param mixed $socket A file pointer that acts as a socet to the server
 * 
 * @return string $message The message that was received from the server
 */
function recv($socket) {
    $message = fgets($socket, 1024);
    flush();
    if (strlen($message) > 4) {
        echo "[RECV] $message";
    }
    return $message;
}

/**
 * Parse a line of input received from the server.
 * 
 * @param mixed $socket A file pointer that acts as a socet to the server
 * 
 * @return array $tokens The tokens from the line of input
 */
function parse_line($line) {
    $tokens = explode(':', $line);
    if (count($tokens) < 3) {
        if (substr($line, 0, 4) == "PING") {
            return "pong";
        } else {
            return null;
        }
    } else {
        $message_info = $tokens[1];
        $info = explode(' ', $message_info);
        $server = $info[0];
        $status = $info[1];
        $user = $info[2];
        $message = '';
        for ($i = 2; $i < count($tokens); $i++) {
            if ($i == 2) {
                $message .= $tokens[$i];
            } else {
                $message .= ":{$tokens[$i]}";
            }
        }
        
        $tokens = array();
        $tokens['server'] = $server;
        $tokens['status'] = $status;
        $tokens['user'] = $user;
        $tokens['message'] = $message;
        return $tokens;
    }
}
?>