<?php
// FETCH FROM URL
/*
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 
"https://tmi.twitch.tv/group/user/kheartz/chatters");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);

$output = curl_exec($ch);

curl_close($ch);

$output = json_decode($output, true);
print_r($output);
*/

// Readdir
$fp = opendir(__DIR__ . "/classes/IRC/Command/Handler/");
if ($fp) {
    while(($file = readdir($fp)) !== false) {
        if (strpos($file, ".php") !== false) {
            echo "$file\n";
        }
    }
}
?>
