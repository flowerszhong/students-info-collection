<?php 
include 'dbc.php';

/******************* ACTIVATION BY FORM**************************/
if ($_POST['doReset']=='Reset')
{
$err = array();
$msg = array();

foreach($_POST as $key => $value) {
	$data[$key] = filter($value);
}
if(!isEmail($data['user_email'])) {
$err[] = "ERROR - 请输入正确的邮箱地址"; 
}

$user_email = $data['user_email'];

//check if activ code and user is valid as precaution
$rs_check = $db->query("select student_id from students where user_email='$user_email'") or die (print_r($db->errorInfo())); 
$rs_check_row = $rs_check->fetch();

// Match row found with more than 1 results  - the user is authenticated. 
if (!$rs_check_row['student_id'] ) { 
  $err[] = "Error - Sorry no such account exists or registered.";
  //header("Location: forgot.php?msg=$msg");
  //exit();
}


if(empty($err)) {

$new_pwd = GenPwd();
$pwd_reset = PwdHash($new_pwd);
//$sha1_new = sha1($new);	
//set update sha1 of new password + salt
$rs_activ = $db->exec("update students set pwd='$pwd_reset' WHERE 
						 user_email='$user_email'") or die(print_r($db->errorInfo()));
						 
$host  = $_SERVER['HTTP_HOST'];
$host_upper = strtoupper($host);						 
						 
//send email


$message = 
"<p>以下账号新密码</p>
<ul>
    <li>邮箱: $user_email </li>
    <li>密码: $new_pwd</li>
</ul>

<p>Administrator</p>
<p>______________________________________________________</p>
该邮件为系统自动发现，请不要回复。<br/>
THIS IS AN AUTOMATED RESPONSE. <br/>
***DO NOT RESPOND TO THIS EMAIL****<br/>
";


require("smtp/smtp.php"); 
########################################## 
$smtpserver = "smtp.163.com";//SMTP服务器 
$smtpserverport = 25;//SMTP服务器端口 
$smtpusermail = "srxhzyh@163.com";//SMTP服务器的用户邮箱 
$smtpemailto = "flowerszhong@gmail.com";//发送给谁 
$smtpuser = "srxhzyh";//SMTP服务器的用户帐号 
$smtppass = "3961908";//SMTP服务器的用户密码 
$mailsubject = "更改密码成功";//邮件主题 
$mailbody = $message;//邮件内容 
$mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件 
########################################## 
$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证. 
$smtp->debug = false;//是否显示发送的调试信息 
$smtpOK = $smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype); 


//$msg = urlencode();
//header("Location: forgot.php?msg=$msg");						 
//exit();
 }
}
?>
<html>
<head>
<title>Forgot Password</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="js/jquery.validate.js"></script>
  <script>
  $(document).ready(function(){
    $("#actForm").validate();
  });
  </script>
<link href="styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr> 
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr> 
    <td width="160" valign="top"><p>&nbsp;</p>
      <p>&nbsp; </p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p></td>
    <td width="732" valign="top">
<h3 class="titlehdr">Forgot Password</h3>

      <p> 
        <?php
	  /******************** ERROR MESSAGES*************************************************
	  This code is to show error messages 
	  **************************************************************************/
	if(!empty($err))  {
	   echo "<div class=\"msg\">";
	  foreach ($err as $e) {
	    echo "* $e <br>";
	    }
	  echo "</div>";	
	   }
	   if(!empty($msg))  {
	    echo "<div class=\"msg\">" . $msg[0] . "</div>";

	   }
	  /******************************* END ********************************/	  
	  ?>
      </p>
      <p>If you have forgot the account password, you can <strong>reset password</strong> 
        and a new password will be sent to your email address.</p>
	 
      <form action="forgot.php" method="post" name="actForm" id="actForm" >
        <table width="65%" border="0" cellpadding="4" cellspacing="4" class="loginform">
          <tr> 
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr> 
            <td width="36%">Your Email</td>
            <td width="64%"><input name="user_email" type="text" class="required email" id="txtboxn" size="25"></td>
          </tr>
          <tr> 
            <td colspan="2"> <div align="center"> 
                <p> 
                  <input name="doReset" type="submit" id="doLogin3" value="Reset">
                </p>
              </div></td>
          </tr>
        </table>
        <div align="center"></div>
        <p align="center">&nbsp; </p>
      </form>
	  
      <p>&nbsp;</p>
	   
      <p align="left">&nbsp; </p></td>
    <td width="196" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="3">&nbsp;</td>
  </tr>
</table>

</body>
</html>
