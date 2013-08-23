<? require_once	("{$_SERVER['DOCUMENT_ROOT']}/_inc/site.files.php"); ?>
<? $adminPage = true ?>
<? include_once	("{$_SERVER['DOCUMENT_ROOT']}/_inc/inc.adminhead.php"); ?>

<div id="adminPage">
	
	<h1>reunion admin area - generate thumbs</h1>
	
	<? include	("{$_SERVER['DOCUMENT_ROOT']}/_inc/inc.admin.nav.php"); ?>
	
	<p>
	<?
	if($pics[0]!=""){
		$x = 0;
		foreach ($pics as $p){
			if(!file_exists("{$thumbsfolder}t_{$p}")){
				imagecopyresampledselection($p, 100, 100, 0, "center");
				print "resizing {$p},<br />";
				$x = $x + 1;
			}
		}
	}
	print	"all done.";
	?>
	</p>
	<p>you've just generated <strong><?= $x ?></strong> thumbnails.</p>
	
</div>

<? include_once	("{$_SERVER['DOCUMENT_ROOT']}/_inc/inc.adminfoot.php"); ?>