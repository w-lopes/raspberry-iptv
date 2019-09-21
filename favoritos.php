<br/>
<br/>
<br/>
<table class="mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp">
	<thead>
		<tr>
			<th class="mdl-data-table__cell--non-numeric">Canais</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$files = [];
		$cat   = __DIR__ . DIRECTORY_SEPARATOR . "lists" . DIRECTORY_SEPARATOR . "favs" . DIRECTORY_SEPARATOR;
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