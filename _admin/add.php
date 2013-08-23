<? require_once	("{$_SERVER['DOCUMENT_ROOT']}/_inc/site.files.php"); ?>
<? $adminPage = true ?>
<? include_once	("{$_SERVER['DOCUMENT_ROOT']}/_inc/inc.adminhead.php"); ?>

<div id="adminPage">
	
	<h1>reunion admin area - add pics to database</h1>
	
	<? include	("{$_SERVER['DOCUMENT_ROOT']}/_inc/inc.admin.nav.php"); ?>
	
	<p>
	<?
	if(getFiles()!=-1){
		$getFiles 	= getFiles();
		$dbFiles	=	array();
		while (!$getFiles->EOF){
			$dbFiles[]	=	$getFiles->fields['file'];
			$getFiles->MoveNext();
		}
	}else{
		// DBERROR
		echo	$dbError;
	}
	
	$x = 0;
	for($i=0;$i<sizeof($pics);$i++){
		if(!in_array($pics[$i],$dbFiles)){
			if(addPic($pics[$i])){
				print "added $pics[$i],<br />";
				$x = $x + 1;
			}else{
				print "couldn't add $pics[$i],<br />";
			}
		}
	}
	print "all done.";
	?>
	</p>
	<p>you've just added <strong><?= $x ?></strong> pics to the database.</p>
</div>

<? include_once	("{$_SERVER['DOCUMENT_ROOT']}/_inc/inc.adminfoot.php"); ?>