<?php 
/* 
这是一个测试程序!!! 
*/ 
require("smtp.php"); 
########################################## 
$smtpserver = "smtp.163.com";//SMTP服务器 
$smtpserverport = 25;//SMTP服务器端口 
$smtpusermail = "srxhzyh@163.com";//SMTP服务器的用户邮箱 
$smtpemailto = "flowerszhong@gmail.com";//发送给谁 
$smtpuser = "srxhzyh";//SMTP服务器的用户帐号 
$smtppass = "3961908";//SMTP服务器的用户密码 
$mailsubject = "请激活您的账号";//邮件主题 
$mailbody = "<h1>不要重复</h1>不要重复";//邮件内容 
$mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件 
########################################## 
$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证. 
$smtp->debug = false;//是否显示发送的调试信息 
$smtpOK = $smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype); 
echo $smtpOK;

?>