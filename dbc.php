<?php
/*************** PHP LOGIN SCRIPT V 2.3*********************
(c) Balakrishnan 2010. All Rights Reserved

Usage: This script can be used FREE of charge for any commercial or personal projects. Enjoy!

Limitations:
- This script cannot be sold.
- This script should have copyright notice intact. Dont remove it please...
- This script may not be provided for download except from its original site.

For further usage, please contact me.

/******************** MAIN SETTINGS - PHP LOGIN SCRIPT V2.1 **********************
Please complete wherever marked xxxxxxxxx

/************* MYSQL DATABASE SETTINGS *****************
1. Specify Database name in $dbname
2. MySQL host (localhost or remotehost)
3. MySQL user name with ALL previleges assigned.
4. MySQL password

Note: If you use cpanel, the name will be like account_database
*************************************************************/

define ("DB_HOST", "localhost"); // set database host
define ("DB_USER", "root"); // set database user
define ("DB_PASS","mzhong1986"); // set database password
define ("DB_NAME","schooldb445"); // set database name

if(!file_exists(DB_NAME))
{
	$db = new PDO('sqlite:'.DB_NAME.'.db'); 
	if($db)
	{	
		$sql = "
			CREATE TABLE `students` (
			  `student_id` INTEGER NOT NULL PRIMARY KEY,
			  `md5_id` varchar(200)  NOT NULL default '',
			  `user_name` text NOT NULL default '',
			  `user_email` varchar(220) NOT NULL default '',
			  `user_level` INTEGER(4) default '1',
			  `pwd` varchar(220) NOT NULL default '',
			  
			  `gender` int(1) default '0',
			  `tel` varchar(200) default '',
			  `building` text,

			  `department` varchar(200) NOT NULL default '',
			  `major` varchar(200) NOT NULL default '',
			  `grade` varchar(200) NOT NULL default '',
			  `class` varchar(200) NOT NULL default '',
			  
			  `reg_date` text NOT NULL default '0000-00-00',
			  `users_ip` varchar(200) NOT NULL default '', 
			  `approved` int(1) NOT NULL default '0',
			  `activation_code` int(10) NOT NULL default '0',

			  `banned` int(1) default '0',
			  `ckey` varchar(220) default '',
			  `ctime` varchar(220) default '',

			  `net_id` varchar(220) default '',
			  `net_pwd` varchar(220) default '',
			  
			  `last_pay_date` text,
			  `expire_date` text,
			  `recharging` int(1) default '1',
			  `recharged` int(1) default '0'
			)";
	    $db->exec($sql);
	}else{
		die("Couldn't select database");
	}
}else{
	$db = new PDO('sqlite:'.DB_NAME.'.db'); 
}

/* Registration Type (Automatic or Manual) 
 1 -> Automatic Registration (Users will receive activation code and they will be automatically approved after clicking activation link)
 0 -> Manual Approval (Users will not receive activation code and you will need to approve every user manually)
*/
$user_registration = 1;  // set 0 or 1

define("COOKIE_TIME_OUT", 10); //specify cookie timeout in days (default is 10 days)
define('SALT_LENGTH', 9); // salt for password

//define ("ADMIN_NAME", "admin"); // sp

/* Specify user levels */
define ("ADMIN_LEVEL", 5);
define ("HEADER_LEVEL", 2);
define ("USER_LEVEL", 1);
define ("GUEST_LEVEL", 0);



/*************** reCAPTCHA KEYS****************/
$publickey = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";
$privatekey = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";


/**** PAGE PROTECT CODE  ********************************
This code protects pages to only logged in users. If users have not logged in then it will redirect to login page.
If you want to add a new page and want to login protect, COPY this from this to END marker.
Remember this code must be placed on very top of any html or php page.
********************************************************/

function page_protect() {
session_start();

global $db; 

/* Secure against Session Hijacking by checking user agent */
if (isset($_SESSION['HTTP_USER_AGENT']))
{
    if ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT']))
    {
        logout();
        exit;
    }
}

// before we allow sessions, we need to check authentication key - ckey and ctime stored in database

/* If session not set, check for cookies set by Remember me */
if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_name']) ) 
{
	if(isset($_COOKIE['user_id']) && isset($_COOKIE['user_key'])){
	/* we double check cookie expiry time against stored in database */
	
	$cookie_user_id  = filter($_COOKIE['user_id']);
	$rs_ctime = sqlite_query("select 'ckey','ctime' from 'users' where 'id' ='$cookie_user_id'") or die(sqliteerror());
	list($ckey,$ctime) = sqlite_fetch_array($rs_ctime);
	// coookie expiry
	if( (time() - $ctime) > 60*60*24*COOKIE_TIME_OUT) {

		logout();
		}
/* Security check with untrusted cookies - dont trust value stored in cookie. 		
/* We also do authentication check of the 'ckey' stored in cookie matches that stored in database during login*/

	 if( !empty($ckey) && is_numeric($_COOKIE['user_id']) && isUserID($_COOKIE['user_name']) && $_COOKIE['user_key'] == sha1($ckey)  ) {
	 	  session_regenerate_id(); //against session fixation attacks.
	
		  $_SESSION['user_id'] = $_COOKIE['user_id'];
		  $_SESSION['user_name'] = $_COOKIE['user_name'];
		/* query user level from database instead of storing in cookies */	
		  list($user_level) = sqlite_fetch_array(sqlite_query("select user_level from users where id='$_SESSION[user_id]'"));

		  $_SESSION['user_level'] = $user_level;
		  $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
		  
	   } else {
		logout();
	   }

  } else {
	header("Location: login.php");
	exit();
	}
}
}



function filter($data) {
	$data = trim(htmlentities(strip_tags($data)));
	
	if (get_magic_quotes_gpc())
		$data = stripslashes($data);
	
	$data = mysql_real_escape_string($data);
	
	return $data;
}



function EncodeURL($url)
{
	$new = strtolower(ereg_replace(' ','_',$url));
	return($new);
}

function DecodeURL($url)
{
	$new = ucwords(ereg_replace('_',' ',$url));
	return($new);
}

function ChopStr($str, $len) 
{
    if (strlen($str) < $len)
        return $str;

    $str = substr($str,0,$len);
    if ($spc_pos = strrpos($str," "))
            $str = substr($str,0,$spc_pos);

    return $str . "...";
}	

function isEmail($email){
  return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;
}

function isUserID($username)
{
	if (preg_match('/^[a-z\d_]{5,20}$/i', $username)) {
		return true;
	} else {
		return false;
	}
 }	


 function get_Datetime_Now() {
     $tz_object = new DateTimeZone('Brazil/East');
     //date_default_timezone_set('Brazil/East');

     $datetime = new DateTime();
     $datetime->setTimezone($tz_object);
     return $datetime->format('Y\-m\-d');
 }

function isUserName($username)
{
	return true;
}
 
function isURL($url) 
{
	if (preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $url)) {
		return true;
	} else {
		return false;
	}
} 

function checkPwd($x,$y) 
{
if(empty($x) || empty($y) ) { return false; }
if (strlen($x) < 4 || strlen($y) < 4) { return false; }

if (strcmp($x,$y) != 0) {
 return false;
 } 
return true;
}

function GenPwd($length = 7)
{
  $password = "";
  $possible = "0123456789bcdfghjkmnpqrstvwxyz"; //no vowels
  
  $i = 0; 
    
  while ($i < $length) { 

    
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
       
    
    if (!strstr($password, $char)) { 
      $password .= $char;
      $i++;
    }

  }

  return $password;

}

function GenKey($length = 7)
{
  $password = "";
  $possible = "0123456789abcdefghijkmnopqrstuvwxyz"; 
  
  $i = 0; 
    
  while ($i < $length) { 

    
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
       
    
    if (!strstr($password, $char)) { 
      $password .= $char;
      $i++;
    }

  }

  return $password;

}




function logout()
{
	global $db;
	session_start();

	$sess_user_id = strip_tags(mysql_real_escape_string($_SESSION['user_id']));
	$cook_user_id = strip_tags(mysql_real_escape_string($_COOKIE['user_id']));

	if(isset($sess_user_id) || isset($cook_user_id)) {
	mysql_query("update 'users' 
				set 'ckey'= '', 'ctime'= '' 
				where 'id'='$sess_user_id' OR  'id' = '$cook_user_id'") or die(mysql_error());
}		

/************ Delete the sessions****************/
unset($_SESSION['user_id']);
unset($_SESSION['user_name']);
unset($_SESSION['user_level']);
unset($_SESSION['HTTP_USER_AGENT']);
session_unset();
session_destroy(); 

/* Delete the cookies*******************/
setcookie("user_id", '', time()-60*60*24*COOKIE_TIME_OUT, "/");
setcookie("user_name", '', time()-60*60*24*COOKIE_TIME_OUT, "/");
setcookie("user_key", '', time()-60*60*24*COOKIE_TIME_OUT, "/");

header("Location: login.php");
}

// Password and salt generation
function PwdHash($pwd, $salt = null)
{
    if ($salt === null)     {
        $salt = substr(md5(uniqid(rand(), true)), 0, SALT_LENGTH);
    }
    else     {
        $salt = substr($salt, 0, SALT_LENGTH);
    }
    return $salt . sha1($pwd . $salt);
}

function checkAdmin() {
	if($_SESSION['user_level'] == ADMIN_LEVEL) {
		return 1;
	} else { 
		return 0 ;
	}
}

?>