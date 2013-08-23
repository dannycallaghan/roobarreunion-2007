<? require_once	("{$_SERVER['DOCUMENT_ROOT']}/_inc/site.files.php"); ?>
<? $adminPage = true ?>
<? include_once	("{$_SERVER['DOCUMENT_ROOT']}/_inc/inc.adminhead.php"); ?>

<div id="adminPage">
	
	<h1>reunion admin area - administer pics</h1>
	
	<? include	("{$_SERVER['DOCUMENT_ROOT']}/_inc/inc.admin.nav.php"); ?>
	
	<?
	if(getImages()!=-1){
		$getPics = getImages();
		if($getPics){
			?>
			<div class="adminPagerLeft">
				<?= pagerAdminPageInfo($getPics); ?>
			</div>
			<div class="adminPagerRight">
				<?= pagerAdminLinks($getPics); ?>
			</div>
			<div class="clear">&nbsp;</div>
			<div class="hr">&nbsp;</div>
			<?	
			for($i=0;$i<sizeof($getPics['data']);$i++){
			?>
		<div class="adminPic">
			<div class="pic">
				<a href="#" title="click to edit"><img src="/_i/_p/_tn/t_<?= $getPics['data'][$i]['file'] ?>" alt="<?= $getPics['data'][$i]['name'] ?>" /></a>
			</div>
			<p><strong>added</strong>: <?= $getPics['data'][$i]['added'] ?></p>	
			<p><strong>id</strong>: <?= $getPics['data'][$i]['id'] ?></p>	
			<p><strong>name/description</strong>:<br /><?= $getPics['data'][$i]['name'] ?></p>
			<p><strong>tags</strong>:
			<?
			$thetags = getTagsByPic($getPics['data'][$i]['id']);
			
			if($thetags==-1){
				print "can't get the tags";
			}else{
				while (!$thetags->EOF){
					print $thetags->fields['tag'] . ' ';
					$thetags->MoveNext();
				}
			}
			?>
			<br /></p>		
			<p><strong>display</strong>: <?= ($getPics['data'][$i]['display']==1)? 'yes':'no' ?></p>
			<form action="/_admin/edit.php" method="post">
				<input type="hidden" name="pic" value="<?= $getPics['data'][$i]['id'] ?>" />
				<input type="hidden" name="referer" value="<?= $sPHPSelf . "?" . $sArgs ?>" />
				<input title="edit this pic" type="submit" value="edit this pic" />
			</form>
			<div class="clear">&nbsp;</div>
		</div>
		<div class="hr">&nbsp;</div>
			<?
			}
			?>
			<div class="adminPagerLeft">
				<?= pagerAdminPageInfo($getPics); ?>
			</div>
			<div class="adminPagerRight">
				<?= pagerAdminLinks($getPics); ?>
			</div>
			<div class="clear">&nbsp;</div>
			<?	
		}else{
		?>
		<p>you don't have any pics in the database.</p>
		<?
		}
	}else{
		//DBERROR
		echo	$dbError;
	}
	?>
	
</div>

<? include_once	("{$_SERVER['DOCUMENT_ROOT']}/_inc/inc.adminfoot.php"); ?>