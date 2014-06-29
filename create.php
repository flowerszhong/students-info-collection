<?php 
include 'dbc.php';
page_protect();

if(!checkAdmin()) {
	header("Location: login.php");
	exit();
}

$host  = $_SERVER['HTTP_HOST'];
$host_upper = strtoupper($host);
$login_path = @ereg_replace('admin','',dirname($_SERVER['PHP_SELF']));
$path   = rtrim($login_path, '/\\');

// filter GET values
foreach($_GET as $key => $value) {
	$get[$key] = filter($value);
}

foreach($_POST as $key => $value) {
	$post[$key] = filter($value);
}

if($_POST['doSubmit'] == 'Create')
{
$rs_dup = $db->query("select count(*) as total from students where user_name='$post[user_name]' OR user_email='$post[user_email]'") or die(print_r($db->errorInfo()));
$dups = $rs_dup->fetch();

if($dups > 0) {
	die("The user name or email already exists in the system");
	}

if(!empty($_POST['pwd'])) {
  $pwd = $post['pwd'];	
  $hash = PwdHash($post['pwd']);
 }  
 else
 {
  $pwd = GenPwd();
  $hash = PwdHash($pwd);
  
 }
 
$db->exec("INSERT INTO students (`user_name`,`user_email`,`pwd`,`approved`,`date`,`user_level`)
			 VALUES ('$post[user_name]','$post[user_email]','$hash','1',now(),'$post[user_level]')
			 ") or die(print_r($db->errorInfo())); 



$message = 
"Thank you for registering with us. Here are your login details...\n
User Email: $post[user_email] \n
Passwd: $pwd \n

*****LOGIN LINK*****\n
http://$host$path/login.php

Thank You

Administrator
$host_upper
______________________________________________________
THIS IS AN AUTOMATED RESPONSE. 
***DO NOT RESPOND TO THIS EMAIL****
";

if($_POST['send'] == '1') {

	mail($post['user_email'], "Login Details", $message,
    "From: \"Member Registration\" <auto-reply@$host>\r\n" .
     "X-Mailer: PHP/" . phpversion()); 
 }
echo "<div class=\"msg\">User created with password $pwd....done.</div>"; 
}


include 'includes/head.php';
include 'includes/sidebar.php';


	  ?>
	  
	  <div class="main">	
	  <h3 class="title">
	  	
	  	创建账号
	  </h3>


      <form name="form1" method="post" action="admin.php">
              <p>User ID 
                <input name="user_name" type="text" id="user_name">
                (Type the username)</p>
              <p>Email 
                <input name="user_email" type="text" id="user_email">
              </p>
              <p>User Level 
                <select name="user_level" id="user_level">
                  <option value="1">User</option>
                  <option value="5">Admin</option>
                </select>
              </p>
              <p>Password 
                <input name="pwd" type="text" id="pwd">
                (if empty a password will be auto generated)</p>
              <p> 
                <input name="send" type="checkbox" id="send" value="1" checked>
                Send Email</p>
              <p> 
                <input name="doSubmit" type="submit" id="doSubmit" value="Create">
              </p>
            </form>
           

	  </div>

    <script src="assets/js/settings.js"></script>
	  
      

<?php 
include 'includes/footer.php'
 ?>
