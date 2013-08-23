<? require_once("{$_SERVER['DOCUMENT_ROOT']}/_inc/site.files.php"); ?>
<?
if(!isset($_GET['pic'])){
	if(!isset($_POST['pic'])){
		header('Location: /');
	}else{
		$pic = $_POST['pic']; 
	}
}else{
	$pic = $_GET['pic']; 
}
$picinfo = getImage($pic);
if(!is_array($picinfo)){
	header('Location: /');
}else if($picinfo[display]==0){
	header('Location: /');
}
$ref 		= (isset($_POST['referer']))? $_POST['referer']:$sHTTPRef;
if(substr_count($ref,'.com')){
	$foo = explode('.com',$ref);
	$ref = $foo[1];
}

if(isset($_POST['name'])){
	$hasPosted 	= false;
	$canPost	= true;
	$name 		= strtolower(trim($_POST['name']));
	$email 		= strtolower(trim($_POST['email']));
	$display	= (isset($_POST['displayemail']))? 1:0;
	$comment	= trim($_POST['comment']);
	if($oSession->getValue("pic{$pic}")!=$comment){
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
		if(!strlen($comment)){
			$commentsError = true;
			$canPost	= false;
		}
		if($canPost){
			if(!addComment($pic,$name,$email,$display,$comment)){
				$hasPosted 		= true;
				$postedComments = "<p>oh dear. look, this was built by snoop and he's <em>still</em> always drunk. needless to say, there's been an error. try again lateron.</p>";
			}else{
				$hasPosted 		= true;	
				$postedComments = "<p>your comment has been added. hope it was funny.</p>";
				$oSession->setValue("pic{$pic}",$comment);
			}
			$name 		= '';
			$email 		= '';
			$comment	= '';
		}
	}else{
		$name 		= '';
		$email 		= '';
		$comment	= '';
	}
}
?>
<? include_once("{$_SERVER['DOCUMENT_ROOT']}/_inc/inc.head.php"); ?>


<script type="text/javascript">
	hs.registerOverlay(
    	{
    		thumbnailId: null,
    		overlayId: 'controlbar',
    		position: 'top right',
    		hideOnMouseOut: true
		}
	);
    hs.graphicsDir = '/_i/highslide/';
    window.onload = function() {
        hs.preloadImages(5);
    }
</script>

	<div id="contentCol">
		
		<div id="homeComment" class="homeBlock">
			<h2>keep it clean</h2>
			<p>go on then, if you've got something clever to say. but don't be cussing (too badly), or slagging johnnie or snoop off or we'll have to remove your post.</p>
			<ul>
				<li><a href="#post" title="post comment"><img src="/_i/buttonpost.gif" alt="post comment" /></a></li>
				<li><a href="<?= $ref  ?>" title="back"><img src="/_i/buttonback.gif" alt="back" /></a></li>
			</ul>
			<div class="clear">&nbsp;</div>
		</div>
	
		<div id="commentthumb" class="thumb fifth">
			<a href="/_i/_p/<?= $picinfo[file] ?>" title="click to view" onclick="return hs.expand(this, {captionId: 'caption<?= $i ?>'})"><img src="/_i/_p/_tn/t_<?= $picinfo[file] ?>" alt="<?= $picinfo[name] ?>" /></a>
		</div>
		
		<div class="highslide-caption" id="caption<?= $i ?>">
			<? 
			if($picinfo[name]){ 
			?>
			<h3><?= $picinfo[name] ?></h3>
			<?
			}
			$thetags = getTagsByPic($picinfo[id]);
			if($thetags!=-1){
			?>
			<div class="leftCol">
				<dl>
					<dt>tags:</dt>
					<?
						$s = 0;
						while (!$thetags->EOF){
							$s++;
							$sep 	= ($s==$thetags->RecordCount())? '':', ';
							$tabs 	= ($s==$thetags->RecordCount())? "\t\t\t\t":"\t\t\t\t\t";
							print "<dd><a href=\"/tags/" . cleanTag($thetags->fields['tag']) . "/\" title=\"{$thetags->fields['tag']}\">{$thetags->fields['tag']}</a>{$sep}</dd>\n{$tabs}";
							$thetags->MoveNext();
						}
						print "</dl>\n";
						print "\t\t\t</div>\n";
						}
					?>
		</div>
		
		<div id="controlbar" class="highslide-overlay controlbar">
			<ul>
				<li id="controlLeft"><a href="#" onclick="return hs.previous(this)" onfocus="this.blur()" title="previous pic">previous pic</a></li>
				<li id="controlRight"><a href="#" onclick="return hs.next(this)" onfocus="this.blur()" title="next pic">next pic</a></li>
				<li id="controlMove"><a href="#" onclick="return false" onfocus="this.blur()" class="highslide-move" title="move pic">move pic</a></li>
				<li id="controlClose"><a href="#" onclick="return hs.close(this)" onfocus="this.blur()" title="close pic">close pic</a></li>
			</ul>
			<div class="clear">&nbsp;</div>
		</div>
		
		<div class="clear">&nbsp;</div>

		<div class="hr">&nbsp;</div>
		
		<?
		$comments = getComments($pic);
		if($comments!=-1){
			while (!$comments->EOF){
		?>

			<div class="comment">
				<!-- comment id = <?= $comments->fields['id'] ?> -->
				<?
					$commentPerson = 	"<h3>";
					if($comments->fields['displayemail']){
						$commentPerson .=	"<a href=\"mailto:" . str_replace('@','[at]',$comments->fields['email']) . "\" title=\"" . str_replace('@','[at]',$comments->fields['email']) . "\">";
					}
					$commentPerson .=	$comments->fields['name'];
					if($comments->fields['displayemail']){
						$commentPerson .=	"</a>";
					}
					$commentPerson .=	" wrote</h3>";
					print $commentPerson;	
				?>
				<p class="date">at <?= $comments->fields['posted'] ?></p>
				<p><?= nl2br($comments->fields['comments']) ?></p>
			</div>
		<?	
				$comments->MoveNext();
			}
		}
		?>
		
		<div id="homeCommentLower" class="homeBlock noMargin">
			<h2>your comments</h2>
			<p><span class="blue">*</span> indicates required field. no html code in the comments please. carriage returns will be converted to line breaks for you. we'll only print your email address if you allow us to. your email will also be altered slightly (the @ symbol removed) so you won't receive spam. well, you will, but it won't be our fault.</p>
		</div>
		
		<div class="clear">&nbsp;</div>
		
		<form id="post" action="<?= $sPHPSelf ?>" method="post" onsubmit="return checkCommentsForm();">
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
				<div class="label">
					<label for="commentEmailDisplay">display email?</label>
				</div>
				<div class="input">
					<input type="checkbox" class="checkbox" name="displayemail" id="commentEmailDisplay" checked="checked" />
				</div>
				<div class="clear">&nbsp;</div>
			</div>
			<div class="formRow">
				<p<?= (!$commentsError)? " class=\"commentError\"":'' ?> id="commentErrorComments">er... your comments?</p>
				<div class="label">
					<label for="commentComments"><span class="blue">*</span> your comments</label>
				</div>
				<div class="input">
					<textarea name="comment" id="commentComments" rows="10" cols="10"><?= (isset($comment))? stripslashes($comment):'' ?></textarea>
				</div>
				<div class="clear">&nbsp;</div>
			</div>
			<div class="formRow">
				<div class="label">&nbsp;</div>
				<div class="input">
					<input type="image" src="/_i/buttonpostsm.gif" class="button" name="save" value="post" title="post" alt="post" />
				</div>
				<div class="clear">&nbsp;</div>
			</div>
		</form>
		
	</div>
		
	<div id="sideCol">
		<? include_once("{$_SERVER['DOCUMENT_ROOT']}/_inc/inc.nav.php"); ?>
		
	</div>
		
<? include_once("{$_SERVER['DOCUMENT_ROOT']}/_inc/inc.foot.php"); ?>