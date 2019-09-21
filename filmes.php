<?php

$ch  = __DIR__ . DIRECTORY_SEPARATOR . "lists" . DIRECTORY_SEPARATOR . "files" . DIRECTORY_SEPARATOR;
$cat = null;
if (isset($_GET["cat"]) && $_GET["cat"]){
	
	$cat = str_replace([".", "/", "\\"], "", $_GET["cat"]);

	if ($cat){
		$cat = is_dir($ch . $cat) ? $ch . $cat : null;
	}
}
if ($cat){
?>
<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored" onclick="_open('filmes.php');">
	Voltar
</button>
<br/>
<br/>
<br/>
<table class="mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp">
	<thead>
		<tr>
			<th class="mdl-data-table__cell--non-numeric">Arquivos</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$files = [];
		foreach (new DirectoryIterator($cat) as $file) {
			if ($file->isDot()){
				continue;
			} else {
				$files[] = $file->getFileName();
			}
		}
		sort($files);
		foreach ($files as $file) {
			$url = trim(file_get_contents($cat . DIRECTORY_SEPARATOR . $file));
			?><tr><td onclick="_play('<?php echo $url;?>');" class="mdl-data-table__cell--non-numeric"><?php echo $file;?></td></tr><?php
		}
		?>
	</tbody>
</table>
<?php
} else {
?>
<table class="mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp">
	<thead>
		<tr>
			<th class="mdl-data-table__cell--non-numeric">Categoria</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$dirs = [];
		foreach (new DirectoryIterator($ch) as $file) {
			if ($file->isDot()){
				continue;
			} else {
				$dirs[] = $file->getFileName();
			}
		}
		sort($dirs);
		foreach ($dirs as $dir) {
			?><tr><td onclick="_open('filmes.php?cat=<?php echo urlencode($dir);?>')" class="mdl-data-table__cell--non-numeric"><?php echo $dir;?></td></tr><?php
		}
		?>
	</tbody>
</table>
<?php
}