<?
/* ######################
roobarreunion.com
site.sql.functions.php
site sql functions
###################### */

// gets number of tags
function getTagsCount(){
	global $server;
	global $userid;
	global $password;
	global $database;
	$db = &NewADOConnection('mysql');
	$db->Connect($server, $userid, $password, $database);
	$sql = "	SELECT COUNT(*) AS total
				FROM tbl_tags";
	$result = $db->Execute($sql); 		
	if(!$result){
		// DBERROR
		$total = -1;
	}else{
		$total = $result->fields['total'];
	}
	$db->Close();
	return $total;
}

// gets the number of comments by pic 
function getTotalComments($pic){
	global $server;
	global $userid;
	global $password;
	global $database;
	$db = &NewADOConnection('mysql');
	$db->Connect($server, $userid, $password, $database);
	$sql = "	SELECT COUNT(id) AS total
				FROM tbl_comments
				WHERE pic = $pic";
	$result = $db->Execute($sql); 		
	if(!$result){
		// DBERROR
		$total = -1;
	}else{
		$total = $result->fields['total'];
	}
	$db->Close();
	return $total;
}

// gets the tags
function getTags(){
	global $server;
	global $userid;
	global $password;
	global $database;
	$db = &NewADOConnection('mysql');
	$db->Connect($server, $userid, $password, $database);
	$sql = "	SELECT id, tag
				FROM tbl_tags
				ORDER BY tag";
	$getTags = $db->Execute($sql);
	if($db->ErrorMsg()){
		// DBERROR
		$getTags = -1;
	}
	$db->Close();
	return $getTags;
}

// gets the pics' file names
function getFiles(){
	global $server;
	global $userid;
	global $password;
	global $database;
	$db = &NewADOConnection('mysql');
	$db->Connect($server, $userid, $password, $database);
	$sql = "	SELECT file
				FROM tbl_pics
				ORDER BY file";
	$getFiles = $db->Execute($sql);
	if($db->ErrorMsg()){
		// DBERROR
		$getFiles = -1;
	}
	$db->Close();
	return $getFiles;
}

// inserts a pic into the database
function addPic($file){
	global $server;
	global $userid;
	global $password;
	global $database;
	$db = &NewADOConnection('mysql');
	$db->Connect($server, $userid, $password, $database);
	$sql = "	INSERT INTO tbl_pics
						(file,added)
					VALUES
						('$file','" . date(Y.'-'.m.'-'.d) . "')";
	$getFiles = $db->Execute($sql);
	if($db->ErrorMsg()){
		// DBERROR
		return false;
	}
	$db->Close();
	return true;
}

// gets all pics info
function getImages($display=0){
	global $server;
	global $userid;
	global $password;
	global $database;
	global $pager_options;
	$db = &NewADOConnection('mysql');
	$db->Connect($server, $userid, $password, $database);
	$sql = "	SELECT * 
				FROM tbl_pics ";
	if($display==1){
		$sql .= "	WHERE display = 1 ";
	}			
	$sql .= "	ORDER BY added DESC, id DESC";
	$getPics = Pager_Wrapper_ADODB($db, $sql, $pager_options);
	if($db->ErrorMsg()){
		//DB-ERROR
		$getPics = -1;
	}
	$db->Close();
	return $getPics;
}

// gets a single image from an id
function getImage($id){
	global $server;
	global $userid;
	global $password;
	global $database;
	global $pager_options;
	$db = &NewADOConnection('mysql');
	$db->Connect($server, $userid, $password, $database);
	$sql = "	SELECT * 
				FROM tbl_pics
				WHERE id = $id";
	$getPic = $db->GetRow($sql);
	if($db->ErrorMsg()){
		//DB-ERROR
		$getPic = -1;
	}
	$db->Close();
	return $getPic;
}

// check the existance of a tag
function checkNewTag($tag){
	global $server;
	global $userid;
	global $password;
	global $database;
	$db = &NewADOConnection('mysql');
	$db->Connect($server, $userid, $password, $database);
	$sql = "	SELECT id
				FROM tbl_tags
				WHERE tag = '$tag'";
	$result = $db->Execute($sql); 		
	if(!$result){
		//DBERROR
		$total = -1;
	}else{
		$total = $result->fields['id'];
	}
	$db->Close();
	return $total;
}

// adds a new tag			
function addNewTag($tag){
	global $server;
	global $userid;
	global $password;
	global $database;
	$db = &NewADOConnection('mysql');
	$db->Connect($server, $userid, $password, $database);
	$sql = "	INSERT INTO tbl_tags (
					tag
					)
					VALUES(
					'$tag'
					)";
	$add = $db->Execute($sql);
	if(!$add){
		// DBERROR
		return false;
	}
	$db->Close();
	return true;
}

// updates a pic (name/desc, display)
function updatePic($name,$display,$id){
	global $server;
	global $userid;
	global $password;
	global $database;
	$db = &NewADOConnection('mysql');
	$db->Connect($server, $userid, $password, $database);
	$sql = "	UPDATE tbl_pics
				SET 
					name 	= '$name',
					display = $display
				WHERE 
					id = $id
				LIMIT 1";
	$update = $db->Execute($sql);
	if(!$update){
		// DBERROR
		return false;
	}
	$db->Close();
	return true;
}

// update a picture's tags		
function updatePicsTags($tag,$pic){
	global $server;
	global $userid;
	global $password;
	global $database;
	$db = &NewADOConnection('mysql');
	$db->Connect($server, $userid, $password, $database);
	$sql = "	INSERT INTO tbl_pics_tags (
					tag,
					pic
					)
					VALUES(
					$tag,
					$pic
					)";
	$add = $db->Execute($sql);
	if(!$add){
		// DBERROR
		return false;
	}
	$db->Close();
	return true;
}

// clears old tags before new ones can be added		
function clearTags($id){
	global $server;
	global $userid;
	global $password;
	global $database;
	$db = &NewADOConnection('mysql');
	$db->Connect($server, $userid, $password, $database);
	$sql = "	DELETE FROM tbl_pics_tags
				WHERE pic = $id";
	$delete = $db->Execute($sql);
	if(!$delete){
		// DBERROR
		return false;
	}
	$db->Close();
	return true;
}

// gets tags for a single pic
function getTagsByPic($id){
	global $server;
	global $userid;
	global $password;
	global $database;
	$db = &NewADOConnection('mysql');
	$db->Connect($server, $userid, $password, $database);
	$sql = "	SELECT tag.* FROM tbl_tags tag
				INNER JOIN tbl_pics_tags pt
				ON pt.tag = tag.id
				INNER JOIN tbl_pics pic
				ON pic.id = pt.pic
				WHERE pic.id = $id
				ORDER BY tag.tag";
	$getTags = $db->Execute($sql);
	if($db->ErrorMsg()){
		// DBERROR
		$getTags = -1;
	}
	$db->Close();
	return $getTags;
}

// gets the data for the tag cloud
function getTagsForCloud(){
	global $server;
	global $userid;
	global $password;
	global $database;
	$db = &NewADOConnection('mysql');
	$db->Connect($server, $userid, $password, $database);
	$sql = "	SELECT tag.tag AS tag, COUNT(pt.id) AS quantity 
				FROM tbl_pics_tags pt
				INNER JOIN tbl_tags tag
				ON tag.id = pt.tag
				INNER JOIN tbl_pics pic
				ON pt.pic = pic.id
				WHERE pic.display = 1
				GROUP BY tag 
				ORDER BY tag ASC";
	$getTags = $db->Execute($sql);
	if($db->ErrorMsg()){
		// DBERROR
		$getTags = -1;
	}
	$db->Close();
	return $getTags;
}

// gets all pics with a certain tag
function getImagesByTag($tag){
	global $server;
	global $userid;
	global $password;
	global $database;
	global $pager_options;
	$db = &NewADOConnection('mysql');
	$db->Connect($server, $userid, $password, $database);
	$sql = "	SELECT pic.*
				FROM tbl_pics pic
				INNER JOIN tbl_pics_tags pt
				ON pic.id = pt.pic
				INNER JOIN tbl_tags tag
				ON tag.id = pt.tag
				WHERE tag.tag = '$tag'
				AND pic.display = 1
				ORDER BY pic.added DESC, pic.id DESC";
	$getPics = Pager_Wrapper_ADODB($db, $sql, $pager_options);
	if($db->ErrorMsg()){
		//DB-ERROR
		$getPics = -1;
	}
	$db->Close();
	return $getPics;
}

// add a comment to a pic
function addComment($pic,$name,$email,$display,$comment){
	global $server;
	global $userid;
	global $password;
	global $database;
	$db = &NewADOConnection('mysql');
	$db->Connect($server, $userid, $password, $database);
	$sql = "	INSERT INTO tbl_comments
						(pic, name, email, displayemail, comments, added)
					VALUES
						($pic, '$name', '$email', $display, '$comment', '" . date(Y.'-'.m.'-'.d.' '.G.':'.i.':'.s) . "')";
	$post = $db->Execute($sql);
	if($db->ErrorMsg()){
		// DBERROR
		return false;
	}
	$db->Close();
	return true;
}

// gets all comments about a pic
function getComments($pic){
	global $server;
	global $userid;
	global $password;
	global $database;
	global $pager_options;
	$db = &NewADOConnection('mysql');
	$db->Connect($server, $userid, $password, $database);
	$sql = "	SELECT id, name, email, comments, DATE_FORMAT(added, '%l:%i%p on %M %D, %Y') AS posted, display , displayemail
				FROM tbl_comments
				WHERE pic = $pic
				AND display = 1
				ORDER BY added ASC";
	$comments = $db->Execute($sql);
	if($db->ErrorMsg()){
		// DBERROR
		$comments = -1;
	}
	$db->Close();
	return $comments;
}

// gets latest comments
function getLatestComments(){
	global $server;
	global $userid;
	global $password;
	global $database;
	global $pager_options;
	$db = &NewADOConnection('mysql');
	$db->Connect($server, $userid, $password, $database);
	$sql = "	SELECT com.pic AS pic, com.id AS id, com.name AS name, com.email AS email, com.comments AS comments, DATE_FORMAT(com.added, '%l:%i%p on %M %D, %Y') AS posted, com.display AS display , com.displayemail AS displayemail
				FROM tbl_comments com
				INNER JOIN tbl_pics pic
				ON pic.id = com.pic
				WHERE pic.display = 1
				AND com.display = 1
				ORDER BY com.added DESC
				LIMIT 24";
	$comments = $db->Execute($sql);
	if($db->ErrorMsg()){
		// DBERROR
		$comments = -1;
	}
	$db->Close();
	return $comments;
}
?>