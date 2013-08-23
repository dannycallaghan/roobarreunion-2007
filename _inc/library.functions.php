<?
/* ######################
roobarreunion.com
library.functions.php
useful variables/arrays/functions library
###################### */

// usefuls
$sDocRoot 	= $_SERVER["DOCUMENT_ROOT"];
$sPHPSelf 	= $_SERVER["PHP_SELF"];
$sArgs 		= $_SERVER['QUERY_STRING'];
$sHTTPRef 	= isset($_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER']:'';
$dToday 	= date(@Y.'-'.@m.'-'.@d);

// titles
$aTitles = array(
		'0'	=>	'Select',
		'1'	=>	'Mr.',
		'2'	=>	'Mrs.',
		'3'	=>	'Ms.',  
		'4'	=>	'Miss.',  
		'5'	=>	'Dr.',  
		'6'	=>	'Prof.',
		'7'	=>	'Other'          
);

// gets the title from title code
// SEE $aTitles
function getTitle($num){
	global $aTitles;
	return $aTitles[$num];
}

// SEE md5_decrypt, md5_encrypt
function get_rnd_iv($iv_len){
   	$iv = '';
   	while ($iv_len-->0){
		$iv .= chr(mt_rand() & 0xff);
   	}
   	return $iv;
}

// encrypts a string
// $secret = "i smell";
// e.g. md5_encrypt($secret,$sKey);
// SEE md5_decrypt(), $sKey
function md5_encrypt($plain_text, $password, $iv_len = 16){
	$plain_text .= "\x13";
   	$n = strlen($plain_text);
   	if ($n % 16) $plain_text .= str_repeat("\0", 16 - ($n % 16));
   	$i = 0;
   	$enc_text = get_rnd_iv($iv_len);
   	$iv = substr($password ^ $enc_text, 0, 512);
   	while ($i < $n) {
    	$block = substr($plain_text, $i, 16) ^ pack('H*', md5($iv));
       	$enc_text .= $block;
       	$iv = substr($block . $iv, 0, 512) ^ $password;
       	$i += 16;
   }
   return base64_encode($enc_text);
}

// decrypts a string
// $sEncryptedSecret = "IIiev8gQFc7zztMOaChqHHGp6YEBbpNBmrfEUNx3rgE=";
// e.g. md5_decrypt($secret,$sKey);
// SEE md5_encrypt(), $sKey
function md5_decrypt($enc_text, $password , $iv_len = 16){
	$enc_text = base64_decode($enc_text);
   	$n = strlen($enc_text);
   	$i = $iv_len;
   	$plain_text = '';
   	$iv = substr($password ^ substr($enc_text, 0, $iv_len), 0, 512);
   	while ($i < $n) {
   		$block = substr($enc_text, $i, 16);
       	$plain_text .= $block ^ pack('H*', md5($iv));
       	$iv = substr($block . $iv, 0, 512) ^ $password;
       	$i += 16;
   	}
   	return preg_replace('/\\x13\\x00*$/', '', $plain_text);
}

// standard (hacky) redirect
// e.g. redirect("/index.php");
function redirect($topage){
	echo "<meta http-equiv=\"refresh\" content=\"0; url={$topage}\" /> ";
}

// creates directories recursively
// e.g. makeDirs("{$_SERVER['DOCUMENT_ROOT']}/months/march",0777);
function makeDirs($strPath, $mode = 0755){
   return is_dir($strPath) or ( makeDirs(dirname($strPath), $mode) and mkdir($strPath, $mode) );
}

// deletes all folders, sub folders and files
// e.g. rmDirAndFiles("{$_SERVER['DOCUMENT_ROOT']}/months/");
function rmDirAndFiles($dir){
   if (substr($dir, strlen($dir)-1, 1) != '/')
       $dir .= '/';
   if ($handle = opendir($dir)){
       while ($obj = readdir($handle)){
           if ($obj != '.' && $obj != '..'){
               if (is_dir($dir.$obj)){
                   if (!rmDirAndFiles($dir.$obj))
                       return false;
               }
               elseif (is_file($dir.$obj)){
                   if (!unlink($dir.$obj))
                       return false;
               }
           }
       }
       closedir($handle);
       if (!@rmdir($dir))
           return false;
       return true;
   }
   return false;
}

// padds a number out with zeros
// e.g. zerofill(8,5);
function zerofill($num,$zerofill){
   while(strlen($num)<$zerofill){
       $num = "0" . $num;
   }
   return $num;
}

// checks if string is empty. " " won't pass either
// e.g. strNotEmpty($_POST['name']);
function strNotEmpty($str){
	return preg_match('/[0-9a-zA-Z]/',$str);
}

// checks a number if it exists
// checkNumber($_POST['telephone'])
function checkNumber($int,$required){
	if($required&&!strNotEmpty($int)){
		return false;
	}
	if(strNotEmpty($int)&&!is_numeric($int)){
		return false;
	}
	return true;
}

// confirms an email address, only if the first one was ok
// checkConfirmedEmail($_POST['email'],$_POST['email2'])
// SEE checkEmail()
function checkConfirmedEmail($str1,$str2){
	if(strNotEmpty($str1)){
		if(checkEmail($str1)){
			if($str1!=$str2){
				return false;
			}
		}
	}
	return true;
}

// confirms a password, only if the first one was ok
// decrypts an already encrypted password before checking too
// checkConfirmedPassword($_POST['password'],$_POST['password2'],true)
// SEE checkPassword()
function checkConfirmedPassword($str1,$str2,$encrypt=false){
	global $sKey;
	if($encrypt){
		$str1 = md5_decrypt($str1,$sKey);
	}
	if(strNotEmpty($str1)){
		if(checkPassword($str1)){
			if($str1!=$str2){
				return false;
			}
		}
	}
	return true;
}

// checks an email address
// e.g. checkEmail($_POST['email']);
function checkEmail($email) {
  if(!ereg("^[^@]{1,64}@[^@]{1,255}$",$email)){
    return false;
  }
  $email_array = explode("@",$email);
  $local_array = explode(".",$email_array[0]);
  for($i = 0; $i < sizeof($local_array);$i++){
     if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])){
      return false;
    }
  }  
  if(!ereg("^\[?[0-9\.]+\]?$",$email_array[1])){
    $domain_array = explode(".",$email_array[1]);
    if(sizeof($domain_array)< 2){
        return false;
    }
    for($i = 0;$i< sizeof($domain_array);$i++){
      if(!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])){
        return false;
      }
    }
  }
  return true;
}

// makes uk date (dd/mm/yyyy) international/sql friendly (yyyy-mm-dd)
// e.g. sqlDate("31/10/1975");
// SEE normalDate()
function intDate($date){
	$exp = explode("/",$date);
	$rev = array_reverse($exp);
	$newdate = '';
	for($i=0;$i<sizeof($rev);$i++){
		$newdate .= $rev[$i] . "-";
	}
	$date = substr($newdate,0,strlen($strtest)-1);
	return $date;
}

// makes international/sql friendly date (yyyy-mm-dd) uk friendly (dd/mm/yyyy)
// e.g. ukDate("1975-10-31");
// SEE sqlDate()
function ukDate($date){
	$exp = explode("-",$date);
	$rev = array_reverse($exp);
	$newdate = '';
	for($i=0;$i<sizeof($rev);$i++){
		$newdate .= $rev[$i] . "/";
	}
	$date = substr($newdate,0,strlen($strtest)-1);
	return $date;
}

// generates a random string, handy for passwords
// e.g generatePassword();
function generatePassword($length = 8){
  $password = "";
  $possible = "0123456789bcdfghjkmnpqrstvwxyz"; 
  $i = 0; 
  while ($i<$length){ 
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
    if (!strstr($password, $char)){ 
      $password .= $char;
      $i++;
    }
  }
  return $password;
}

// tests string for passwordability
// e.g checkPassword("password")
function checkPassword($password){
	if(	ctype_alnum($password) // numbers & digits only
		&& strlen($password)>=6 // at least X chars
		&& strlen($password)<=10 // at most X chars
		// && preg_match('`[A-Z]`',$password) // at least one upper case
		// && preg_match('`[a-z]`',$password) // at least one lower case
		&& preg_match('`[0-9]`',$password) // at least one digit
	){
		return true;
	}
	return false;
}

// generates random string
// no vowels to avoid offensive words
// no lower case L to avoid confusion
// e.g generateString(10)
function generateString($length = 8){
  $string = "";
  $possible = "bcdfghjkmnpqrstvwxyz"; 
  $i = 0; 
  while ($i < $length) { 
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
    if (!strstr($string, $char)) { 
      $string .= $char;
      $i++;
    }
  }
  return $string;
}

// generates random number as string
// e.g generateInt(10)
function generateInt($length = 8){
  $int = "";
  $possible = "0123456789"; 
  $i = 0; 
  while ($i < $length) { 
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
   		$int .= $char;	
   	 	$i++;
  }
  return $int;
}

// formats a name nicely
// e.g formatName("DANNY CALLAGHAN")
function formatName($str){
	if(!$str){
		return;
	}
	$str = trim($str);
	$str = explode(" ",$str);
	for($i=0;$i<sizeof($str);$i++){
		$foo = strtoupper(substr($str[$i],0,1));
		$bar = strtolower(substr($str[$i],1,strlen($str[$i])));
		$new = $foo . $bar . " ";
	}
	$str = trim($new);
	return $str;
}

// formats a mysql date to a nice one
function normalDate($date){
	$exp = explode("-",$date);
	$rev = array_reverse($exp);
	$newdate = '';
	for($i=0;$i<sizeof($rev);$i++){
		$newdate .= $rev[$i] . "/";
	}
	$date = substr($newdate,0,strlen($newdate)-1);
	return $date;
}

// removes everything but numbers from a telephone
// e.g formatName("800-1213-121 ")			
function formatTelephone($str){
	$foo = preg_replace('/[^0-9]/','',$str);
	return $foo;
}

// formats a web addreess
// e.g formatWebsiteAddress("http://WWW.MYSITE.com")	
function formatWebsiteAddress($str){
	$str = strtolower(trim($str));
	if(substr_count($str,'http://')){
		$str = substr($str,7,strlen($str));
	}
	return $str;
}

// tests if a number is odd
// e.g. is_odd(12)
function is_odd($number) {
   return $number & 1; // 0 = even, 1 = odd
}

// prepares a date for validation
function validateDate($str,$required=false,$future){
	if(!$required&&!strNotEmpty($str)){
		return true;
	}
	$foo = explode('/',$str);
	if(is_array($foo)){
		if(sizeof($foo)==3){
			if(!checkValidDate($day=$foo[0],$month=$foo[1],$year=$foo[2],$future)){
				return false;
			}
		}else{
			return false;
		}
	}else{
		return false;
	}
	return true;
}

// cleans a keyword before searching
function cleanSearchWord($str){
	$foo = preg_replace('/[^0-9a-zA-Z-]/','',$str);
	return $foo;
}

// removes spaces
function removeSpaces($str){
	$foo = str_replace(' ','',$str);
	return $foo;
}

// checks a date, can be past or not past
function checkValidDate($day,$month,$year,$future=true){
	if(!$day||!$month||!$year){
		return false;
	}
	if($year<=999){
		return false;
	}
	if(!checkdate($month,$day,$year)){
		return false;
	}
	$date = new Date();
	$date->setYear($year);
	$date->setDay($day);
	$date->setMonth($month);
	if($future){
		if($date->isPast()){
			return false;
		}
	}
	return true;
}
?>