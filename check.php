<?php

$base = $argv[1];
$cat  = $argv[2] ?: "Untitled";
$file = $argv[3];
$url  = $argv[4];
$path = $base . $cat . DIRECTORY_SEPARATOR;
$on   = trim(shell_exec("curl -m 1 --output /dev/null --silent --head --fail {$url} && echo \"sim\" || echo \"nao\""));

if ($on !== "sim"){
	exit;
}

if (!is_dir($path)){
	mkdir($path);
}

file_put_contents($path . $file, $url);