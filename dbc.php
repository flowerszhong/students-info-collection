<?php

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
define ("DB_NAME","schooldb9x22.php"); // set database name
declare(encoding='UTF-8');


$filename = $_SERVER['DOCUMENT_ROOT']."/students-info-collection/".DB_NAME;
if(!file_exists($filename))
{	
	$db = new PDO('sqlite:'.DB_NAME); 
	if($db)
	{	
		$sql = "
			CREATE TABLE `students` (
			  `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
			  `student_id` INTEGER,
			  `md5_id` varchar(200) default '',
			  `user_name` varchar(200) NOT NULL default '',
			  `user_email` varchar(220) default '',
			  `user_level` INTEGER(4) default '1',
			  `pwd` varchar(220) default '',
			  
			  `gender` int(1) default '0',
			  `tel` varchar(200) default '',
			  `building` text,

			  `department` varchar(200) default '',
			  `department_id` INTEGER default '',
			  `major` varchar(200) default '',
			  `major_id` INTEGER default '',
			  `grade` INTEGER default 2014,
			  `class` varchar(200) default '',
			  `class_id` INTEGER default '',
			  
			  `reg_date` text default '0000-00-00',
			  `users_ip` varchar(200) default '', 
			  `approved` int(1) default '0',
			  `activation_code` int(10) NOT NULL default '0',

			  `banned` int(1) default '0',
			  `ckey` varchar(220) default '',
			  `ctime` varchar(220) default '',

			  `net_id` varchar(220) default '',
			  `net_pwd` varchar(220) default '',
			  
			  `last_pay_date` text,
			  `expire_date` text,
			  `recharging` int(1) default '1',
			  `recharged` int(1) default '0',
			  `fee` INTEGER default '0',
			  `consumer_record`	BLOB,
			  `recharge_fee` INTEGER
			)";
	    $db->exec($sql);


	    $sql_payments = "
	    	CREATE TABLE `payments` (
	    		`pay_id` INTEGER NOT NULL PRIMARY KEY,
			  `student_id` INTEGER NOT NULL,
			  `pay_date` text,
			  `pay_amount` text,
			  `pay_month` INTEGER,
			  `pay_ok` int(1) default '0'
			)";
		$db->exec($sql_payments);


		$sql_departments = "CREATE TABLE `departments` (
			`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
			`name`	TEXT NOT NULL,
			`code`	TEXT NOT NULL
		)";

		$db->exec($sql_departments);


		$sql_departments_data = "INSERT INTO `departments`(`name`,`code`) SELECT '环境工程与土木工程系','gcx' UNION ALL 
									SELECT '环境科学系','hkx' UNION ALL
									SELECT '环境监测系','jcx' UNION ALL
									SELECT '机电工程系','jdgc' UNION ALL
									SELECT '生态环境系','sthj' UNION ALL
									SELECT '环境艺术与服务系','hjys' UNION ALL
									SELECT '循环经济与低碳经济系','xxjj' UNION ALL";

		$db->exec($sql_departments_data);


		$sql_majors = "CREATE TABLE `majors` (
			`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
			`department_id`	INTEGER NOT NULL,
			`name`	TEXT,
			`code`	TEXT
		)";
		$db->exec($sql_majors);


		$sql_accounts = "CREATE TABLE `accounts` (
			`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
			`account_id`	TEXT,
			`account_pwd`	TEXT,
			`user_id`	INTEGER,
			`used`	INTEGER,
			`account_type`	INTEGER
		)";

		$db->exec($sql_accounts);


	}else{
		die("Couldn't select database");
	}
}else{
	$db = new PDO('sqlite:'.$filename); 
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
	$rs_ctime = $db->query("select 'ckey','ctime' from 'students' where 'student_id' ='$cookie_user_id'") or die(sqliteerror());
	$rs_ctime_row = $rs_ctime->fetch();

	list($ckey,$ctime) = $rs_ctime_row;

	// var_dump($ckey);

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
		$rs_userlevel = $db->query("select user_level from students where id='$_SESSION[user_id]'");
		  $user_level_row = $rs_userlevel->fetch();
		  $user_level = $user_level_row['user_level'];
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
	// $data = trim(htmlentities(strip_tags($data)));//fuck htmlentities,not for chinese
	$data = trim(htmlspecialchars(strip_tags($data)));


	
	if (get_magic_quotes_gpc())
		$data = stripslashes($data);
	
	$data = sqlite_escape_string($data);
	

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
	// var_dump($db);

	$sess_user_id = strip_tags(sqlite_escape_string($_SESSION['user_id']));
	$cook_user_id = strip_tags(sqlite_escape_string($_COOKIE['user_id']));

	if(isset($sess_user_id) || isset($cook_user_id)) {
		// echo $sess_user_id;
		// echo $cook_user_id;
		// $sql_clear = "update students set 'ckey'= '', 'ctime'= '' where 'student_id'='$sess_user_id' OR 'student_id' = '$cook_user_id'";
		$sql_update = "UPDATE students SET 'ckey'= '', 'ctime'= '' where 'student_id'='$sess_user_id' OR 'student_id' = '$cook_user_id'";
		// $sql_clear = "update 'students' set 'ckey'= '', 'ctime'= '' where 'student_id'='$sess_user_id'";
		// echo $sql_clear;
		$update_result = $db->exec($sql_update);

		if($update_result === false)
		{
		    print_r($db->errorInfo());
		    $error = $db->errorInfo();
		    die ("Error: (".$error[0].':'.$error[1].') '.$error[2]);
		}
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

	header("Location: index.php");
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
	// var_dump($_SESSION);
	// echo 'user_level'.$_SESSION['user_level'];
	if($_SESSION['user_level'] == ADMIN_LEVEL) {
		return 1;
	} else { 
		return 0 ;
	}
}

function strToUtf8 ($vector)
{
    $from_chr= mb_detect_encoding($vector,array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));
    // echo $from_chr;
    if($from_chr!="UTF-8")
    {
        $vector = iconv($from_chr, "UTF-8", $vector);
    }
    return $vector;
}


function showDBError ()
{
	global $db;
	print_r($db->errorInfo());
}

function getLastInsertID()
{
	global $db;
	$sql_select = "SELECT last_insert_rowid() as last_insert_cid";
	 
	$rrows = $db->query($sql_select) or die(showDBError());

	$rows = $rrows->fetch();

	if($rows['last_insert_cid']!== false){
		return $rows['last_insert_cid'];
	}else{
		return false;
	}
}

function getUserName()
{
	global $db;
	$id = $_SESSION['user_id'];
	$sql_select = "SELECT user_name as user_name from students where id=$id";
	$rrows = $db->query($sql_select) or die(showDBError());
	$rows = $rrows->fetch();

	if($row['user_name']!==''){
		$_SESSION['user_name'] = $row['user_name'];
		return $rows['user_name'];
	}else{
		return $_SESSION['user_name'];

	}

}


function sendEmail($subject,$message,$email)
{
	require("smtp/smtp.php"); 
	########################################## 
	$smtpserver = "smtp.163.com";//SMTP服务器 
	$smtpserverport = 25;//SMTP服务器端口 
	$smtpusermail = "srxhzyh@163.com";//SMTP服务器的用户邮箱 
	$smtpemailto = $email;//发送给谁 
	$smtpuser = "srxhzyh";//SMTP服务器的用户帐号 
	$smtppass = "3961908";//SMTP服务器的用户密码 
	$mailsubject = $subject;//邮件主题 
	$mailbody = $message;//邮件内容 
	$mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件 
	########################################## 

	$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证. 
	$smtp->debug = false;//是否显示发送的调试信息 
	$smtpOK = $smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype); 

	return $smtpOK;
}



?>