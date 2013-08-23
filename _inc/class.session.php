<?
/* ######################
session.class.php
session handlers
###################### */

class Session{

    // creates a session
	// e.g. $oSession = new Session();
	function Session(){
        session_start();
    }
	
	// set a session variable
	// e.g. $oSession->setValue('username',$_POST['username']);
	function setValue($var_name,$var_val){
    	if(!$var_name||!$var_val){
            return false;
        }
        $_SESSION[$var_name] = $var_val;
    }
	
	// get a session variable
	// e.g. $username = $oSession->getValue('user');
    function getValue($var_name){
        return $_SESSION[$var_name];
    }
	
	// deletes a session variable
	// e.g. $oSession->delValue('user');
    function delValue($var_name){
        unset($_SESSION[$var_name]);
    }
	
	// deletes several session variables
	// e.g. $oSession->delValues('user','id');
    function delValues($arr){
        if(!is_array($arr)){
            return false;
        }
        foreach($arr as $element){
            unset($_SESSION[$element]);
        }
        return true;
    }
	
	// deletes all session variables
	// e.g. $oSession->delAllValues();
    function delAllValues(){
       unset($_SESSION);
    }
	
	// ends session
	// e.g. $oSession->endSession();
    function endSession(){
        $_SESSION = array();
        session_destroy();
    }

}

$oSession = new Session();
?>