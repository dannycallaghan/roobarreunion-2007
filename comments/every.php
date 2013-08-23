<? require_once("{$_SERVER['DOCUMENT_ROOT']}/_inc/site.files.php"); ?>
<? include_once("{$_SERVER['DOCUMENT_ROOT']}/_inc/inc.head.php"); ?>

	<div id="contentCol">
		
		<div id="homeCommentLower" class="homeBlock">
			<h2>latest comments</h2>
			<p>let's hope you've all been funny and original as the latest couple o'dozen comments are below. hit 'read' to see what the hell they're talking about.</p>
		</div>
		
		<div class="clear">&nbsp;</div>
		
		<?
		$comments = getLatestComments();
		if($comments!=-1){
			$s = 0;
			while (!$comments->EOF){
				$s++;
				$end = ($s==$comments->RecordCount())? ' noBorder':'';
			
		?>

			<div class="comment<?= $end ?>">
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
				<ul>
					<li><a href="/comments/<?= $comments->fields['pic'] ?>/" title="read"><img src="/_i/buttonread.gif" alt="read" /></a></li>
				</ul>
			</div>
		<?	
				$comments->MoveNext();
			}
		}
		?>
		
	</div>
		
	<div id="sideCol">
		<? include_once("{$_SERVER['DOCUMENT_ROOT']}/_inc/inc.nav.php"); ?>
		
	</div>
		
<? include_once("{$_SERVER['DOCUMENT_ROOT']}/_inc/inc.foot.php"); ?>