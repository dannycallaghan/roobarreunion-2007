<? require_once	("{$_SERVER['DOCUMENT_ROOT']}/_inc/site.files.php"); ?>
<? $adminPage = true ?>
<? include_once	("{$_SERVER['DOCUMENT_ROOT']}/_inc/inc.adminhead.php"); ?>

<div id="adminPage">
	
	<h1>reunion admin area</h1>
	
	<? include	("{$_SERVER['DOCUMENT_ROOT']}/_inc/inc.admin.nav.php"); ?>

	<h2>process for adding new pics:</h2>
	
	<ul class="standard">
		<li>upload the new pictures</li>
		<li>generate the thumbs</li>
		<li>add pics to the database</li>
		<li>add tags, description, etc (administer pics)</li>
	</ul>
	
</div>

<? include_once	("{$_SERVER['DOCUMENT_ROOT']}/_inc/inc.adminfoot.php"); ?>