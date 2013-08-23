<?
if(!isset($_GET['tag'])){
	header('Location: /');
}
$tag = strtolower($_GET['tag']);
?>
<? require_once("{$_SERVER['DOCUMENT_ROOT']}/_inc/site.files.php"); ?>
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
		<?
		if(getImagesByTag($tag)!=-1){
			$getPics = getImagesByTag($tag);
			if($getPics){
		?>
<div class="pagination">
		<?		
				print "<div class=\"left\">" . pagerPageInfo($getPics,$type=$tag) . "</div>";
				print "<div class=\"right\">" . pagerLinks($getPics) . "</div>"; 
		?>
			<div class="clear">&nbsp;</div>
		</div>
		<?
				$t = 0;
				for($i=0;$i<sizeof($getPics['data']);$i++){
					$t++;
					$isFifth = '';
					if($t==5){
						$isFifth = " fifth";
						$t = 0;
					}
				?>
<!-- start pic | pic id = <?= $getPics['data'][$i]['id'] ?>, pic filename = <?= $getPics['data'][$i]['file'] ?> -->			
		<div class="thumb<?= $isFifth ?>">
			<a href="/_i/_p/<?= $getPics['data'][$i]['file'] ?>" title="click to view" onclick="return hs.expand(this, {captionId: 'caption<?= $i ?>'})"><img src="/_i/_p/_tn/t_<?= $getPics['data'][$i]['file'] ?>" alt="<?= $getPics['data'][$i]['name'] ?>" /></a>
		</div>
		<div class="highslide-caption" id="caption<?= $i ?>"><? 
					if($getPics['data'][$i]['name']){ 
					?>
					
			<h3><?= $getPics['data'][$i]['name'] ?></h3><?
					}
					$thetags = getTagsByPic($getPics['data'][$i]['id']);
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
						print "\t\t\t<div class=\"rightCol\">\n";
						print "\t\t\t\t<ul>\n";
						$totalComs = getTotalComments($getPics['data'][$i]['id']);
						if($totalComs!=-1){
							$s = ($totalComs==1)? '':'s';
							print "\t\t\t\t\t<li><a href=\"/comments/{$getPics['data'][$i]['id']}/\" title=\"view comments\">{$totalComs} comment{$s}</a> | </li>";
						}
						?>		
					<li><a href="/comments/<?= $getPics['data'][$i]['id'] ?>/#post" title="add a comment">add comment</a> | </li>
					<li><a href="/remove/<?= $getPics['data'][$i]['id'] ?>/" title="request removal">request removal</a> | </li>
					<li>pic id = <?= $getPics['data'][$i]['id'] ?></li>
				</ul>
			</div>
			<div class="clear">&nbsp;</div><?
				print "\n\t\t</div>\n\t\t<!-- end pic -->\n\t\t";
				}
				?>
				<div class="clear">&nbsp;</div>
<!-- start controls -->				
		<div id="controlbar" class="highslide-overlay controlbar">
			<ul>
				<li id="controlLeft"><a href="#" onclick="return hs.previous(this)" onfocus="this.blur()" title="previous pic">previous pic</a></li>
				<li id="controlRight"><a href="#" onclick="return hs.next(this)" onfocus="this.blur()" title="next pic">next pic</a></li>
				<li id="controlMove"><a href="#" onclick="return false" onfocus="this.blur()" class="highslide-move" title="move pic">move pic</a></li>
				<li id="controlClose"><a href="#" onclick="return hs.close(this)" onfocus="this.blur()" title="close pic">close pic</a></li>
			</ul><?
				print "\n\t\t\t<div class=\"clear\">&nbsp;</div>\n\t\t</div>\n\t\t<!-- end controls -->\n\t";
		?>		
		<div class="pagination">
		<?		
				print "<div class=\"left\">" . pagerPageInfo($getPics,$type=$tag) . "</div>";
				print "<div class=\"right\">" . pagerLinks($getPics) . "</div>"; 
		?>
			<div class="clear">&nbsp;</div>
		</div>
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
		print "</div>";
		?>
		
	<div id="sideCol">
		<? include_once("{$_SERVER['DOCUMENT_ROOT']}/_inc/inc.nav.php"); ?>
		
	</div>
		
<? include_once("{$_SERVER['DOCUMENT_ROOT']}/_inc/inc.foot.php"); ?>