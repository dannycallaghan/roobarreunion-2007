<? require_once	("{$_SERVER['DOCUMENT_ROOT']}/_inc/site.files.php"); ?>
<?
if(!isset($_POST['pic'])){
	header('Location: /_admin/');
}else{
	$pic 			= $_POST['pic'];
	$ref 		= (isset($_POST['referer']))? $_POST['referer']:$sHTTPRef;
	if(substr_count($ref,'.com')){
		$foo = explode('.com',$ref);
		$ref = $foo[1];
	}
	$siteMsg		= false;
	if(isset($_POST['addtag'])){
		// adding a tag
		$newTag = trim($_POST['newtag']);
		if($newTag==''){
			$siteMsg	 	= true;
			$siteMsgText 	= 'you must enter a tag smartarse';
		}else{
			if(checkNewTag($newTag)==-1){
				$siteMsg 		= true;
				//DBERROR
				$siteMsgText 	= 'problem checking the tag. try again.';
			}else{
				if(checkNewTag($newTag)==''){
					if(!addNewTag($newTag)){
						$siteMsg 		= true;
						//DBERROR
						$siteMsgText 	= 'problem adding the tag. try again.';
					}else{
						$siteMsg 		= true;
						//DBERROR
						$siteMsgText 	= "tag added. don't forget to select.";
					}
				}else{
					$siteMsg 		= true;
					//DBERROR
					$siteMsgText 	= 'we already have that tag.';
				}
			}
		}
	}
	if(isset($_POST['save'])){
		$name 		= $_POST['name'];
		$display 	= isset($_POST['display'])? 1:0;
		$tags		= $_POST['tags'];
		$id			= $_POST['pic'];
		
		if(updatePic($name,$display,$id)){
			$addOne			= true;
			if(clearTags($id)){
				$addTwo = true;
				for($i=0;$i<sizeof($tags);$i++){
					if(!updatePicsTags($tags[$i],$id)){
						$addTwo = false;
						print "error adding $tags[$i]<br />";
						break;
					}
				}
				if(!$addTwo){
					$addTwo			= false;
					$siteMsg		= true;
					$siteMsgText 	= 'there was a problem adding the tags. try again.';
				}else{
					$addTwo = true;
				}
			}else{
				$addTwo			= false;
				$siteMsg		= true;
				$siteMsgText 	= 'there was a problem adding the tags. try again.';
			}
		}else{
			$addOne			= false;
			$siteMsg		= true;
			$siteMsgText 	= 'there was a problem adding the name/description and display info to the database. try again.';
		}
		if($addOne&&$addTwo){
			header("Location: $ref");
		}
	}
}
?>
<? $adminPage = true ?>
<? include_once	("{$_SERVER['DOCUMENT_ROOT']}/_inc/inc.adminhead.php"); ?>

<div id="adminPage">
	
	<h1>reunion admin area - administer pics</h1>
	
	<? include	("{$_SERVER['DOCUMENT_ROOT']}/_inc/inc.admin.nav.php"); ?>
	
	<?
	$details 	= getImage($pic);

	if($details==-1){
		//DBERROR
		echo	$dbError;
	}else{
		if(sizeof($details>0)){
			$file 		= $details['file'];
			$name 		= $details['name'];
			$display 	= $details['display'];
	?>
	<form action="<?= $sPHPSelf ?>" method="post">
		<input type="hidden" name="referer" value="<?= $ref ?>" />
		<input type="hidden" name="pic" value="<?= $pic ?>" />
		<?
		if($siteMsg){
		?>
		<div class="formRow"><p class="formError"><span><?= $siteMsgText ?></span></p></div>
		<?
		}
		?>
		<div class="formRow">
			<div class="label">
				<label for="adminFormName">name/description</label>
			</div>
			<div class="input">
				<input type="text" class="text" name="name" id="adminFormName" value="<?= $name ?>" maxlength="100" />
			</div>
			<div class="clear">&nbsp;</div>
		</div>
		<div class="formRow checkbox">
			<div class="label">
				<label for="adminFormDisplay">display</label>
			</div>
			<div class="input">
				<input type="checkbox" name="display" id="adminFormDisplay"<?= ($display)? " checked=\"checked\"":''?> />
			</div>
			<div class="clear">&nbsp;</div>
		</div>
		<?
		$tags = getTags();
		
		$thetags = getTagsByPic($pic);
			
		if($thetags!=-1){
			while (!$thetags->EOF){
				$has_tags[] = $thetags->fields['id'];
				$thetags->MoveNext();
			}
		}
		if($tags==-1||$thetags==-1){
			//DBERROR
			echo	$dbError;
		}else{
		?>
		<div class="formRow">
			<div class="label">
				<label for="adminFormTags">tags</label>
			</div>
			<div class="input">
				<select multiple="multiple" size="10" id="adminFormTags" name="tags[]">
					<?
					while (!$tags->EOF){
						$selected = (in_array($tags->fields['id'],$has_tags))? " selected=\"selected\"":'';
					?>
						<option value="<?= $tags->fields['id'] ?>"<?= $selected ?>><?= $tags->fields['tag'] ?></option>
					<?	
						$tags->MoveNext();
					}
					?>
				</select>
			</div>
			<div class="clear">&nbsp;</div>
		</div>
		<?	
		}
		?>
		<div class="formRow">
			<div class="label">
				<label for="adminFormAddTag">add new tag</label>
			</div>
			<div class="input">
				<input type="text" class="text" name="newtag" id="adminFormAddTag" maxlength="24" />
				<input type="submit" name="addtag" value="add tag" />
			</div>
			<div class="clear">&nbsp;</div>
		</div>
		<div class="formRow">
			<div class="label">&nbsp;</div>
			<div class="input">
				<input type="submit" name="save" value="save changes" />
			</div>
			<div class="clear">&nbsp;</div>
		</div>
	</form>
	<form action="<?= $ref ?>" method="post">
		<div class="formRow">
			<div class="label">&nbsp;</div>
			<div class="input">
				<input type="submit" name="cancel" value="cancel" />
			</div>
			<div class="clear">&nbsp;</div>
		</div>
	</form>
	
	<img src="/_i/_p/<?= $file ?>" alt="" id="adminMainPic" />
	
	<div class="clear">&nbsp;</div>
	<?		
		}else{
	?>
	<p>that id isn't recognised buddy.</p>	
	<?
		}
	}
	?>
	
</div>

<? include_once	("{$_SERVER['DOCUMENT_ROOT']}/_inc/inc.adminfoot.php"); ?>