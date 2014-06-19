<?php    

set_time_limit(120); 
error_reporting(2);
$smtpserver = "smtp.qq.com"; //您的smtp服务器的地址
$port = 25; //smtp服务器的端口，一般是 25 
$smtpuser = "1426723214"; //您登录smtp服务器的用户名
$smtppwd = "password"; //您登录smtp服务器的密码
$mailtype = "HTML"; //邮件的类型，可选值是 TXT 或 HTML ,TXT 表示是纯文本的邮件,HTML 表示是 html格式的邮件
$sender = "1426723214@qq.com"; //发件人,一般要与您登录smtp服务器的用户名($smtpuser)相同,否则可能会因为smtp服务器的设置导致发送失败
$smtp=new smtp($smtpserver,$port,true,$smtpuser,$smtppwd,$sender); 
$smtp->debug = true; //是否开启调试,只在测试程序时使用，正式使用时请将此行注释
$to = "1426723214@qq.com"; //收件人
$subject = "你好";
$body = "<h1>www.v-mp4.cn <font color='red'><b> php socket </b></font> 发邮件测试。
支持SMTP认证！ </h1>
";
$send=$smtp->sendmail($to,$sender,$subject,$body,$mailtype);
if($send==1){
echo "邮件发送成功";
}else{
echo "邮件发送失败<br>";
echo "原因：".$this->smtp->logs;
}


class smtp
{

/* Public Variables */

var $smtp_port;

var $time_out;

var $host_name;

var $log_file;

var $relay_host;

var $debug;

var $auth;

var $user;

var $pass;

var $sender;

/* Private Variables */
var $sock;

/* Constractor */

function smtp($relay_host = "", $smtp_port = 25,$auth = false,$user,$pass,$sender)
{
$this->debug = FALSE;

$this->smtp_port = $smtp_port;

$this->relay_host = $relay_host;

$this->time_out = 30; //is used in fsockopen() 

$this->auth = $auth;//auth

$this->user = $user;

$this->pass = $pass;

$this->sender = $sender;

$this->host_name = "localhost"; //is used in HELO command 

$this->log_file = "";

$this->logs = ""; //记录跟服务器的交互过程

$this->sock = FALSE;
}

/* Main Function */

function sendmail($to, $from, $subject = "", $body = "", $mailtype, $cc = "", $bcc = "", $additional_headers = "")
{
$sent = TRUE; 

$mail_from = $this->get_address($this->strip_comment($from));

$body = ereg_replace("(^|( ))(.)", ".", $body);

$header .= "MIME-Version:1.0 ";

if($mailtype=="HTML"){

$header .= "Content-Type:text/html ";
}

$header .= "To: ".$to." ";

if ($cc != "") {

$header .= "Cc: ".$cc." ";

}

//$header .= "From: $from<".$from."> ";

$header .= "From: ".$from." ";

$header .= "Subject: ".$subject." ";

$header .= $additional_headers;

$header .= "Date: ".date("r")." ";

$header .= "X-Mailer: 72e.net (PHP/".phpversion().") ";

list($msec, $sec) = explode(" ", microtime());

$header .= "Message-ID: <".date("YmdHis", $sec).".".($msec*1000000).".".$mail_from."> ";

$TO = explode(",", $this->strip_comment($to));

if ($cc != "") {

$TO = array_merge($TO, explode(",", $this->strip_comment($cc)));

}

if ($bcc != "") {

$TO = array_merge($TO, explode(",", $this->strip_comment($bcc)));

}


foreach ($TO as $rcpt_to) {

$rcpt_to = $this->get_address($rcpt_to);

if (!$this->smtp_sockopen($rcpt_to)) {

$this->log_write("Error: Cannot send email to ".$rcpt_to." ");

$sent = FALSE;

continue;

}

if ($this->smtp_send($this->host_name, $mail_from, $rcpt_to, $header, $body)) {

$this->log_write("E-mail has been sent to <".$rcpt_to."> ");

} else {

$this->log_write("Error: Cannot send email to <".$rcpt_to."> ");

$sent = FALSE;

}

fclose($this->sock);

$this->log_write("Disconnected from remote host ");

}

return $sent;

}



/* Private Functions */



function smtp_send($helo, $from, $to, $header, $body = "")
{

if (!$this->smtp_putcmd("HELO", $helo)) {

return $this->smtp_error("sending HELO command");

}

#auth

if($this->auth){

if (!$this->smtp_putcmd("AUTH LOGIN", base64_encode($this->user))) {

return $this->smtp_error("sending AUTH command");

}

if (!$this->smtp_putcmd("", base64_encode($this->pass))) {

return $this->smtp_error("sending AUTH command");

}

}

#

//if (!$this->smtp_putcmd("MAIL", "FROM:".$from."")) {
if (!$this->smtp_putcmd("MAIL", "FROM:<".$this->sender.">")) {

return $this->smtp_error("sending MAIL FROM command");

}

if (!$this->smtp_putcmd("RCPT", "TO:<".$to.">")) {

return $this->smtp_error("sending RCPT TO command");

}

if (!$this->smtp_putcmd("DATA")) {

return $this->smtp_error("sending DATA command");

}

if (!$this->smtp_message($header, $body)) {

return $this->smtp_error("sending message");

}

if (!$this->smtp_eom()) {

return $this->smtp_error("sending <CR><LF>.<CR><LF> [EOM]");

}

if (!$this->smtp_putcmd("QUIT")) {

return $this->smtp_error("sending QUIT command");

}

return TRUE;

}

function smtp_sockopen($address)
{

if ($this->relay_host == "") {

return $this->smtp_sockopen_mx($address);

} else {

return $this->smtp_sockopen_relay();

}
}

function smtp_sockopen_relay()
{
$this->log_write("Trying to ".$this->relay_host.":".$this->smtp_port." ");

$this->sock = @fsockopen($this->relay_host, $this->smtp_port, $errno, $errstr, $this->time_out);

if (!($this->sock && $this->smtp_ok())) {

$this->log_write("Error: Cannot connenct to relay host ".$this->relay_host." ");

$this->log_write("Error: ".$errstr." (".$errno.") ");

return FALSE;

}

$this->log_write("Connected to relay host ".$this->relay_host." ");

return TRUE;
}

function smtp_sockopen_mx($address)
{

$domain = ereg_replace("^.+@([^@]+)$", "", $address);

if (!@getmxrr($domain, $MXHOSTS)) {

$this->log_write("Error: Cannot resolve MX "".$domain."" ");

return FALSE;

}

foreach ($MXHOSTS as $host) {

$this->log_write("Trying to ".$host.":".$this->smtp_port." ");

$this->sock = @fsockopen($host, $this->smtp_port, $errno, $errstr, $this->time_out);

if (!($this->sock && $this->smtp_ok())) {

$this->log_write("Warning: Cannot connect to mx host ".$host." ");

$this->log_write("Error: ".$errstr." (".$errno.") ");

continue;

}

$this->log_write("Connected to mx host ".$host." ");

return TRUE;

}

$this->log_write("Error: Cannot connect to any mx hosts (".implode(", ", $MXHOSTS).") ");

return FALSE;

}



function smtp_message($header, $body)
{
fputs($this->sock, $header." ".$body);

$this->smtp_debug("> ".str_replace(" ", " "."> ", $header." > ".$body." > "));

return TRUE;
}



function smtp_eom()
{
fputs($this->sock, " . ");

$this->smtp_debug(". [EOM] ");

return $this->smtp_ok();
}



function smtp_ok()
{
$response = str_replace( " ", "", fgets($this->sock, 512));

//echo "response=".$response." ";

$this->smtp_debug($response." ");

//echo "ereg 23 ==".ereg("^[23]", $response)." ";

if (!ereg("^[23]", $response)) {

//echo "@@@@@";

fputs($this->sock, "QUIT ");

fgets($this->sock, 512);

$this->log_write("Error: Remote host returned "".$response."" ");

return FALSE;

}

return TRUE;
}

function smtp_putcmd($cmd, $arg = "")
{
if ($arg != "") {

if($cmd=="") $cmd = $arg;

else $cmd = $cmd." ".$arg;

}

fputs($this->sock, $cmd." ");

$this->smtp_debug("> ".$cmd." ");

//echo "cmd=".$cmd." ";

return $this->smtp_ok();
}

function smtp_error($string)
{
$this->log_write("Error: Error occurred while ".$string.". ");

return FALSE;
}

function log_write($message)
{
$this->logs .= $message;

$this->smtp_debug($message);

if ($this->log_file == "") {

return TRUE;
}

$message = date("M d H:i:s ").get_current_user()."[".getmypid()."]: ".$message;

if (!@file_exists($this->log_file) || !($fp = @fopen($this->log_file, "a"))) {

$this->smtp_debug("Warning: Cannot open log file "".$this->log_file."" ");

return FALSE;
}

flock($fp, LOCK_EX);

fputs($fp, $message);

fclose($fp);

return TRUE;
}


function strip_comment($address)
{
$comment = "([^()]*)";

while (ereg($comment, $address)) {

$address = ereg_replace($comment, "", $address);

}

return $address;
}


function get_address($address)
{
$address = ereg_replace( "([  ])+", "", $address);

$address = ereg_replace("^.*<(.+)>.*$", "", $address);

return $address;
}

function smtp_debug($message)

{

if ($this->debug) {

echo $message;

}

}

} // end class
?>