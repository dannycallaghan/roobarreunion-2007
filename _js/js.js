// validates an email address
checkEmail = function(str) {
	var at="@"
	var dot="."
	var lat=str.indexOf(at)
	var lstr=str.length
	var ldot=str.indexOf(dot)
	if (str.indexOf(at)==-1){
		return false
	}
	if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
		return false
	}
	if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
		return false
	}
	if (str.indexOf(at,(lat+1))!=-1){
		return false
	}
	if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		return false
	}
	if (str.indexOf(dot,(lat+2))==-1){
		return false
	}
	if (str.indexOf(" ")!=-1){
		return false
	}
	return true					
}

// validates the comments form
checkCommentsForm = function(){
	//$('postedMessage').className = 'commentError';
	$('commentErrorName').className = 'commentError';
	$('commentErrorEmail').className = 'commentError';
	$('commentErrorComments').className = 'commentError';
	if($('commentName').value==''){
		$('commentErrorName').className = '';
		return false;
	}
	if(!checkEmail($('commentEmail').value)){
		$('commentErrorEmail').className = '';
		return false;
	}
	if($('commentComments').value==''){
		$('commentErrorComments').className = '';
		return false;
	}
	return true;
}

function isNumeric(str){
	var re = /[\D]/g
	if (re.test(str)) return false;
	return true;
}

// validates the removal form
checkRemoveForm = function(){
	//$('postedMessage').className = 'commentError';
	$('commentErrorName').className = 'commentError';
	$('commentErrorEmail').className = 'commentError';
	$('commentErrorPic').className = 'commentError';
	$('commentErrorComments').className = 'commentError';
	if($('commentName').value==''){
		$('commentErrorName').className = '';
		return false;
	}
	if(!checkEmail($('commentEmail').value)){
		$('commentErrorEmail').className = '';
		return false;
	}
	if($('commentPic').value==''){
		$('commentErrorPic').className = '';
		return false;
	}else{
		if(!isNumeric($('commentPic').value)){
			$('commentErrorPic').className = '';
			return false;
		}
	}
	if($('commentComments').value==''){
		$('commentErrorComments').className = '';
		return false;
	}
	return true;
}

function blurAnchors(){
  if(document.getElementsByTagName){
    var a = document.getElementsByTagName("a");
    for(var i = 0; i < a.length; i++){
      a[i].onfocus = function(){this.blur()};
    }
  }
}

