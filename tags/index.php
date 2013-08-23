<? require_once("{$_SERVER['DOCUMENT_ROOT']}/_inc/site.files.php"); ?>
<? include_once("{$_SERVER['DOCUMENT_ROOT']}/_inc/inc.head.php"); ?>

	<div id="contentCol">
		
		<div id="pageTag" class="homeBlock">
			<h2>pics by tag</h2>
			<p>all the tags we've used so far are below. the bigger the tag, <span class="strike">the more important it is</span> the more pics we have. if we've missed someone in a pic, or tagged a photo incorrectly, let us know. our memories are shot.</p>
		</div>
		
		<div class="clear">&nbsp;</div>
		
		<?
		$result = getTagsForCloud();
		while (!$result->EOF){
		    $tags[$result->fields['tag']] = $result->fields['quantity'];
			$result->MoveNext();
		}
		
		$max_size = 250; // max font size in %
		$min_size = 100; // min font size in %
		$max_qty = max(array_values($tags));
		$min_qty = min(array_values($tags));
		
		$spread = $max_qty - $min_qty;
		if (0 == $spread) {
		    $spread = 1;
		}
		$step = ($max_size - $min_size)/($spread);
		?>
		<ul id="tags">
		<?	
		foreach ($tags as $key => $value) {
		    $size = $min_size + (($value - $min_qty) * $step);
		?>
			<li><a href="/tags/<?= cleanTag($key) ?>/" style="font-size: <?= $size ?>%;" title="<?= $key ?>"><?= $key ?></a></li>
		<?    
		}
		?>
		</ul>
	</div>	
		
	<div id="sideCol">
		<? include_once("{$_SERVER['DOCUMENT_ROOT']}/_inc/inc.nav.php"); ?>
		
	</div>
		
<? include_once("{$_SERVER['DOCUMENT_ROOT']}/_inc/inc.foot.php"); ?>