<?
/* ######################
roobarreunion.com
site.functions.php
site specific vars and functions
###################### */

$dbError		=	"<p>oh dear. look, this was built by snoop and he's <em>still</em> always drunk. needless to say, there's been an error. try again lateron.</p>";
$imagefolder	=	"{$_SERVER['DOCUMENT_ROOT']}/_i/_p/";
//$thumbsfolder	=	"{$_SERVER['DOCUMENT_ROOT']}/_i/_p/_t/";
$thumbsfolder	=	"{$_SERVER['DOCUMENT_ROOT']}/_i/_p/_tn/";
$pics			=	directory($imagefolder,"jpg,JPG,JPEG,jpeg");
$pics			=	ditchtn($pics,"t_");

// pagination options
$pager_options = array( 
	'mode' => 'Sliding', 
	'perPage' => 15, 
	'delta' => 4, 
);

function ditchtn($arr,$thumbname){
	foreach ($arr as $item){
		if (!preg_match("/^".$thumbname."/",$item)){$tmparr[]=$item;}
	}
	return $tmparr;
}

function directory($dir,$filters){
	$handle=opendir($dir);
	$files=array();
	if($filters == "all"){while(($file = readdir($handle))!==false){$files[] = $file;}}
	if($filters != "all"){
		$filters=explode(",",$filters);
		while(($file = readdir($handle))!==false){
			for($f=0;$f<sizeof($filters);$f++):
				$system=explode(".",$file);
				if ($system[1] == $filters[$f]){$files[] = $file;}
			endfor;
		}
	}
	closedir($handle);
	return $files;
}

function imagecopyresampledselection($filename, $desired_width, $desired_height, $bordersize, $position){   
   global $resizeTotal;
   global $imagefolder;
   global $thumbsfolder;
   $resizeTotal = 0;
   $newfilename = $filename;
   $filename = $imagefolder . $filename;
	list($width, $height) = getimagesize($filename);
	if($desired_width/$desired_height > $width/$height):
	    $new_width = $desired_width;
	    $new_height = $height * ($desired_width / $width);
	else:
	    $new_width = $width * ($desired_height / $height);
	    $new_height = $desired_height;
	endif;
	$image_p = imagecreatetruecolor($new_width, $new_height);
	$image_f = imagecreatetruecolor($desired_width, $desired_height);
	$image = imagecreatefromjpeg($filename);
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
	switch($position){
	    case("topleft"):
	        $x = $bordersize;
	        $y = $bordersize;
	        break;
	    case("topright"):
	        $x = $new_width - $desired_width + $bordersize;
	        $y = $bordersize;
	        break;
	    case("bottomleft"):
	        $x = $bordersize;
	        $y = $new_height - $desired_height + $bordersize;
	        break;
	    case("bottomright"):
	        $x = $new_width - $desired_width + $bordersize;
	        $y = $new_height - $desired_height + $bordersize;
	        break;
	    case("center"):
	        $x = ($new_width - $desired_width) / 2 + $bordersize;
	        $y = ($new_height - $desired_height) / 2 + $bordersize;
	        break;
	}
	imagecopyresampled($image_f, $image_p, $bordersize, $bordersize, $x, $y,	$desired_width    - 2 * $bordersize,
	                                                                            $desired_height    - 2 * $bordersize,
	                                                                            $desired_width    - 2 * $bordersize,
	                                                                            $desired_height    - 2 * $bordersize);
	imagejpeg($image_f,"{$thumbsfolder}t_".$newfilename,100);
}

// write out the admin "page X of X" stuff
function pagerAdminPageInfo($result){
	$pagerHTML = 	"\t<p><strong>" . $result['totalItems'] . "</strong> pics";
	$pagerHTML .= 	". showing page <strong>" . $result['page_numbers']['current'] . "</strong> of <strong>" . $result['page_numbers']['total'] . "</strong>.</p>\n";
	return $pagerHTML;
}

// write out the "page X of X" stuff
function pagerPageInfo($result,$type=''){
	$pagerHTML = 	"\t<p><strong>" . $result['totalItems'] . "</strong> pics";
	if($type!=''){
		$pagerHTML .=	" of <strong>$type</strong>";
	}
	$pagerHTML .= 	". showing page <strong>" . $result['page_numbers']['current'] . "</strong> of <strong>" . $result['page_numbers']['total'] . "</strong>.</p>\n";
	return $pagerHTML;
}

// writes out the admin pager links
function pagerAdminLinks($result){
	$pageHTML = '';
	if(strlen($result['links'])>0){
		$pageHTML .= "\t\t\t<ul>\n\t\t\t" . $result['links'] ."\n\t\t\t</ul>\n";
	}
	return $pageHTML;
}

// writes out the pager links
function pagerLinks($result){
	$pageHTML = '';
	if(strlen($result['links'])>0){
		$pageHTML .= "\t\t\t<ul>\n\t\t\t" . $result['links'] ."\n\t\t\t</ul>\n";
	}
	return $pageHTML;
}

// cleans a keyword before searching
function cleanTag($str){
	$foo = preg_replace('/[^0-9a-zA-Z-]/','+',$str);
	return $foo;
}

?>