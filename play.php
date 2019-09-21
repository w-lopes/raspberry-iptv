<?php

$url = $_GET["url"];

exec("killall omxplayer");
exec("killall /usr/bin/omxplayer.bin");

if ($url){

	$live = preg_match("/(m3u)/", $url) ? "--live" : "";

	$cmd = "omxplayer --aspect-mode stretch -o hdmi {$live} " . escapeshellcmd($url) . " > /dev/null 2>&1 &";

	exec($cmd);
}