<?php 
include 'dbc.php';


$sql_select = "SELECT * FROM payments";

$rrows = $db->query($sql_select) or die(showDBError());

$rows = $rrows->fetchAll();

var_dump($rows);
$err = array();

foreach($_GET as $key => $value) {
	$get[$key] = filter($value); //get variables are filtered.
}

if ($_POST['doLogin']=='Login')
{

foreach($_POST as $key => $value) {
	$data[$key] = filter($value); // post variables are filtered
}


$user_email = $data['usr_email'];
$pass = $data['pwd'];


if (strpos($user_email,'@') === false) {
    $user_cond = "user_name='$user_email'";
} else {
    $user_cond = "user_email='$user_email'";
}


$sql_select = "SELECT `student_id`,`pwd`,`user_name`,`approved`,`user_level` FROM students WHERE 
           $user_cond
			AND `banned` = '0'";
// echo $sql_select;		
// $result = $db->query($sql_select) or die(print_r($db->errorInfo())); 
$result = $db->query($sql_select); 

// var_dump($result);

$row = $result->fetch();
// var_dump($row);

  // Match row found with more than 1 results  - the user is authenticated. 
    if ($row) { 
	
	list($id,$pwd,$full_name,$approved,$user_level) = $row;

	// $full_name = base64_decode($full_name);
	$approved = true;

	if(!$approved) {
	//$msg = urlencode("Account not activated. Please check your email for activation code");
	$err[] = "Account not activated. Please check your email for activation code";
	
	//header("Location: login.php?msg=$msg");
	 //exit();
	 }
	 
		//check against salt
	if ($pwd === PwdHash($pass,substr($pwd,0,9))) { 
	if(empty($err)){			

     // this sets session and logs user in  
       session_start();
	   session_regenerate_id (true); //prevent against session fixation attacks.

	   // this sets variables in the session 
		$_SESSION['user_id']= $id;  
		$_SESSION['user_name'] = $full_name;
		$_SESSION['user_level'] = $user_level;
		// $_SESSION['user_level'] = ADMIN_LEVEL;
		$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
		// var_dump($_SESSION);

		//update the timestamp and key for cookie
		$stamp = time();
		$ckey = GenKey();
		$db->exec("update students set `ctime`='$stamp', `ckey` = '$ckey' where student_id='$id'") or die($db->errorInfo());
		
		//set a cookie 
		
	   if(isset($_POST['remember'])){
				  setcookie("user_id", $_SESSION['user_id'], time()+60*60*24*COOKIE_TIME_OUT, "/");
				  setcookie("user_key", sha1($ckey), time()+60*60*24*COOKIE_TIME_OUT, "/");
				  setcookie("user_name",$_SESSION['user_name'], time()+60*60*24*COOKIE_TIME_OUT, "/");
				   }
			header("Location: myaccount.php");
			exit();
		}
		}
		else
		{
		//$msg = urlencode("Invalid Login. Please try again with correct user email and password. ");
		$err[] = "Invalid Login. Please try again with correct user email and password.";
		//header("Location: login.php?msg=$msg");
		}
	} else {
		$err[] = "Error - Invalid login. No such user exists";
	  }		
}
					 
					 

?>
<?php 
$page_title = "登录 - 上网自助服务";
include 'includes/head.php';
include 'includes/navbar.php'
?>


<?php
/******************** ERROR MESSAGES*************************************************
This code is to show error messages 
**************************************************************************/
if(!empty($err))  {
	echo "error";
 echo "<div class=\"msg\">";
foreach ($err as $e) {
  echo "$e <br>";
  }
echo "</div>";	
 }
/******************************* END ********************************/	  
?>
<div class="container login-box">
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4">
            <h1 class="text-center login-title">登录并查询上网信息</h1>
            <div class="account-wall">
                <img class="profile-img" src="assets/image/photo.png"
                    alt="">
                <form class="form-signin" action="login.php" method="post" name="logForm">
	                <input type="text" name="usr_email" class="form-control" placeholder="学号或邮箱" required autofocus>
	                <input type="password" name="pwd" class="form-control" placeholder="密码" required>
	                <button class="btn btn-lg btn-primary btn-block"  name="doLogin" value="Login" type="submit">
	                    登录</button>
	                <label class="checkbox pull-left">
	                    <input type="checkbox" value="1" name="remember">
	                    记住我
	                </label>
	                <a href="forgot.php" class="pull-right need-help">忘记密码 </a><span class="clearfix"></span>
                </form>
            </div>
            <a href="register.php" class="text-center new-account">注册账号 </a>
        </div>
    </div>
</div>

</body>
</html>
