<?php

set_time_limit(0);

$channels = [];
$files    = [];
$lists    = [
	// Canais
	"https://bit.ly/faustinotv"
	//"http://iptvvictorapp.ucoz.com.br/ListaVip.txt",
	//"https://pastebin.com/raw/MN3bdVJ5",

	// Series
	//"https://pastebin.com/raw/VsBeC91H",

	// Filmes
	//"http://www.lhmtv.tk/filmes/lhm-filmes.txt",

	// Misto
	//"http://pastebin.com/raw/hQyZWi7S",

	// Listas muito grandes... pode demorar horas para atualizar...
//	"http://linkiptv.me/177",
//	"http://bit.ly/pjt-lista",
//	"http://tvfo.co/tvfoco"
];

function tirarAcentos($string){
    return preg_replace(["/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"],explode(" ","a A e E i I o O u U n N"),$string);
}

function rrmdir($dir) {
	if (is_dir($dir)) {
		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (is_dir($dir . "/" . $object)) {
					rrmdir($dir . "/" . $object);
				} else {
					unlink($dir . "/" . $object);
				}
			}
		}
		rmdir($dir);
	}
}

foreach ($lists as $list) {
	$agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
	$ch    = curl_init();

	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_VERBOSE, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_URL, $list);

	$result = curl_exec($ch);
	$lines  = explode(PHP_EOL, $result);

	curl_close($ch);

	foreach ($lines as $i => $line){
		if (strpos($line, "#EXTINF") === false){
			continue;
		}
		if (!isset($lines[$i + 1])){
			continue;
		}
		$url = trim($lines[$i + 1]);
		if (!filter_var($url, FILTER_SANITIZE_URL)){
			continue;
		}
		if (!preg_match("/(mp4)|(m3u)|(\.ts)/", $url)){
		//if (!preg_match("/(mp4)|(m3u)/", $url)){
			continue;
		}
		$type = preg_match("/(mp4)/", $url) ? "files" : "channels";
		$group = str_replace(array("/", "\"", "."), "", ucfirst(strtolower(tirarAcentos(trim(preg_replace(array(
			"/.*group-title=\"/",
			"/\".*/",
			"/#EXTINF.*,/",
			"/T\d.*/",
			"/S\d.*/",
			"/Ep\s.*/",
			"/Ep\d.*/",
			"/^\d+\s-\s/",
		), "", $line))))));
		if (preg_match("/(\.ts)/", $url)){
			$group = "Canais TS";
		}
		if (!$group){
			continue;
		}
		if (strpos($group, "#EXTINF") !== false || strpos($group, "#extinf") !== false){
			$group = "Sem categoria";
		}
		$name = str_replace(array("/", "\"", "."), "", tirarAcentos(trim(preg_replace(array(
			"/.*,/",
			"/\[[\w\d\s\/]+\]/"
		), "", $line))));
		if (preg_match("/lista paga/i", $name)){
			continue;
		}
		${$type}[$group][] = [
			"url"  => $url,
			"name" => $name
		];
	}
}

umask(0);

$path      = __DIR__ . DIRECTORY_SEPARATOR . "lists" . DIRECTORY_SEPARATOR;
$pchannels = $path . "channels" . DIRECTORY_SEPARATOR;
$pfiles    = $path . "files" . DIRECTORY_SEPARATOR;
$ccount    = 0;
$fcount    = 0;

rrmdir($path);

mkdir($path);
mkdir($pchannels);
mkdir($pfiles);

foreach (array_keys($channels) as $group) {

	foreach ($channels[$group] as $i => $value) {
		$ccount++;
		exec("php " .  __DIR__ . DIRECTORY_SEPARATOR . "check.php " . implode(" ", array(
			escapeshellarg($pchannels),
			escapeshellarg($group),
			escapeshellarg($channels[$group][$i]["name"]),
			escapeshellarg($channels[$group][$i]["url"])
		)) . " > /dev/null 2>&1 &");
	}
}

foreach (array_keys($files) as $group) {

	foreach ($files[$group] as $i => $value) {
		$fcount++;
		exec("php " .  __DIR__ . DIRECTORY_SEPARATOR . "check.php " . implode(" ", array(
			escapeshellarg($pfiles),
			escapeshellarg($group),
			escapeshellarg($files[$group][$i]["name"]),
			escapeshellarg($files[$group][$i]["url"])
		)) . " > /dev/null 2>&1 &");
	}
}

echo nl2br(
	"<h3>Atualizado</h3>" . PHP_EOL .
	"<h5>Pode demorar alguns minutos até que a lista esteja atualizada, está sendo verificado quais canais estão online...</h5>" . PHP_EOL .
	"<b>Link para serem testados: </b> " . ($ccount + $fcount) . PHP_EOL .
	"<b>Canais: </b> {$ccount}" . PHP_EOL .
	"<b>Arquivos: </b> {$fcount}" . PHP_EOL
);