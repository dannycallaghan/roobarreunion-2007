<? require_once("{$_SERVER['DOCUMENT_ROOT']}/_inc/site.files.php"); ?>
<?
if(isset($_GET['pic'])){
	$pic = $_GET['pic']; 
}
if(isset($_POST['pic'])){
	$pic = $_POST['pic']; 
	
}

if(isset($_POST['name'])){
	$hasPosted 	= false;
	$canPost	= true;
	$name 		= strtolower(trim($_POST['name']));
	$email 		= strtolower(trim($_POST['email']));
	$pic		= trim($_POST['pic']);
	$comment	= trim($_POST['comment']);
	if($oSession->getValue("remove{$pic}")!="sent"){
		if(!strlen($name)){
			$nameError 	= true;
			$canPost	= false;
		}
		if(strlen($email)){
			if(!checkEmail($email)){
				$emailError = true;
				$canPost	= false;
			}
		}else{
			$emailError = true;
			$canPost	= false;
		}
		if(!is_numeric($pic)){
			$picError 	= true;
			$canPost	= false;
		}
		if(!strlen($comment)){
			$commentsError = true;
			$canPost	= false;
		}
		if($canPost){
			
			$to = "oneplayer <danny@oneplayer.co.uk>";
		
			$my_subject = "ROOBARREUNION: removal request"; 
			$my_message = "Name: {$name}\n"; 
			$my_message .= "Email: {$email}\n\n";
			$my_message .= "Pic: {$pic}\n\n";
			$my_message .= "Comments: {$comment}\n";  
			
			$my_from .= "From: ROOBARREUNION"; 
			
			$mail = @mail($to, $my_subject, $my_message, $my_from);
			
			if($mail){
				$oSession->setValue("remove{$pic}","sent");
				unset($error);
			}else{
				$error = "having some problems with this at the moment. please try again later. sorry.";
			}
			
			$name 		= '';
			$email 		= '';
			$pic 		= '';
			$comment	= '';
		}
	}else{
		$name 		= '';
		$email 		= '';
		$pic 		= '';
		$comment	= '';
		unset($error);
		$canPost = false;
	}
}
?>
<? include_once("{$_SERVER['DOCUMENT_ROOT']}/_inc/inc.head.php"); ?>

	<div id="contentCol">
		
		<div id="pageRemove" class="homeBlock">
			<h2>remove pic</h2>
			<p>we've put a fair few hours in, getting this site together. as a result, we won't really appreciate requests to take photos down because you think you look fat, or you have a spot on your chin, or it's your wrong side. obviously though, we don't want to upset anyone and we will act upon any proper requests asap. all requests will be completely confidential.</p>
			<?
			if(isset($error)){
				print "<p class=\"blue\">$error</p>";
			}else{
				if($canPost){
					print "<p class=\"blue\">your request has been sent and we promise we'll deal with it asap.</p>";
				}
			}
			?>			
		</div>
		
		<div class="clear">&nbsp;</div>

		<form id="remove" action="<?= $sPHPSelf ?>" method="post" onsubmit="return checkRemoveForm();">
			<input type="hidden" name="pic" value="<?= $pic ?>" />
			<input type="hidden" name="referer" value="<?= $ref ?>" />
			<div class="formRow">
				<p<?= (!$nameError)? " class=\"commentError\"":'' ?> id="commentErrorName">gotta enter your name</p>
				<div class="label">
					<label for="commentName"><span class="blue">*</span> your name</label>
				</div>
				<div class="input">
					<input type="text" maxlength="55" class="text" name="name" id="commentName" value="<?= (isset($name))? stripslashes($name):'' ?>" />
				</div>
				<div class="clear">&nbsp;</div>
			</div>
			<div class="formRow">
				<p<?= (!$emailError)? " class=\"commentError\"":'' ?> id="commentErrorEmail">gotta enter a valid email</p>
				<div class="label">
					<label for="commentEmail"><span class="blue">*</span> your email</label>
				</div>
				<div class="input">
					<input type="text" maxlength="55" class="text" name="email" id="commentEmail" value="<?= (isset($email))? stripslashes($email):'' ?>" />
				</div>
				<div class="clear">&nbsp;</div>
			</div>
			<div class="formRow">
				<p<?= (!$picError)? " class=\"commentError\"":'' ?> id="commentErrorPic">gotta enter a pic's numeric id</p>
				<div class="label">
					<label for="commentPic"><span class="blue">*</span> the photo <span class="small">&dagger;</span></label>
				</div>
				<div class="input">
					<input type="text" maxlength="5" class="textsmall" name="pic" id="commentPic" value="<?= (isset($pic))? $pic:'' ?>" />
				</div>
				<div class="clear">&nbsp;</div>
			</div>
			<div class="formRow">
				<p<?= (!$commentsError)? " class=\"commentError\"":'' ?> id="commentErrorComments">kind of need a reason</p>
				<div class="label">
					<label for="commentComments"><span class="blue">*</span> why for? <span class="small">&Dagger;</span></label>
				</div>
				<div class="input">
					<textarea name="comment" id="commentComments" rows="10" cols="10"><?= (isset($comment))? stripslashes($comment):'' ?></textarea>
				</div>
				<div class="clear">&nbsp;</div>
			</div>
			<div class="formRow">
				<div class="label">&nbsp;</div>
				<div class="input">
					<input type="image" src="/_i/buttonsend.gif" class="button" name="save" value="post" title="send" alt="send" />
				</div>
				<div class="clear">&nbsp;</div>
			</div>
			<div class="formRow">
				<div class="label">&nbsp;</div>
				<div class="input">
					<p class="small">&dagger; you can find a pic's id number under a pic's tags<br />&Dagger; this will remain private</p>
				</div>
				<div class="clear">&nbsp;</div>
			</div>
		</form>

	</div>
		
	<div id="sideCol">
		<? include_once("{$_SERVER['DOCUMENT_ROOT']}/_inc/inc.nav.php"); ?>
		
	</div>
		
<? include_once("{$_SERVER['DOCUMENT_ROOT']}/_inc/inc.foot.php"); ?>