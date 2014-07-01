<?php 
include 'dbc.php';

$err = array();


if(isset($_SESSION['user_id'])){
	echo "sessionid";
	header("Location: myaccount.php");
	die();
}

foreach($_GET as $key => $value) {
	$get[$key] = filter($value); //get variables are filtered.
}

if ($_POST['doLogin']=='Login' || $_POST['doLogin']=='登录')
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


$sql_select = "SELECT `id`,`pwd`,`user_name`,`approved`,`user_level` FROM students WHERE 
           $user_cond";
// echo $sql_select;		
// $result = $db->query($sql_select) or die(print_r($db->errorInfo())); 
$result = $db->query($sql_select); 

// var_dump($result);

$row = $result->fetch();

  // Match row found with more than 1 results  - the user is authenticated. 
    if ($row) { 
	
	$id = $row['id'];
	$pwd = $row['pwd'];
	$user_name = $row['user_name'];
	$approved = $row['approved'];
	$user_level = $row['user_level'];

	$approved = true;

	if(!$approved) {
	//$msg = urlencode("Account not activated. Please check your email for activation code");
	$err[] = "账号尚未激活，请检查你的邮件，激活你的账号";
	
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
		$_SESSION['user_name'] = $user_name;
		$_SESSION['user_level'] = $user_level;
		// $_SESSION['user_level'] = ADMIN_LEVEL;
		$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
		// var_dump($_SESSION);

		//update the timestamp and key for cookie
		$stamp = time();
		$ckey = GenKey();
		$db->exec("update students set `ctime`='$stamp', `ckey` = '$ckey' where id='$id'") or die($db->errorInfo());

		//set a cookie 
		
	   if(isset($_POST['remember'])){
				  setcookie("user_id", $_SESSION['user_id'], time()+60*60*24*COOKIE_TIME_OUT, "/");
				  setcookie("user_key", sha1($ckey), time()+60*60*24*COOKIE_TIME_OUT, "/");
				  setcookie("user_name",$_SESSION['user_name'], time()+60*60*24*COOKIE_TIME_OUT, "/");
				   }

			header("Location: myaccount.php");
			die(); 
		}
		}
		else
		{
		//$msg = urlencode("Invalid Login. Please try again with correct user email and password. ");
		$err[] = "登录出错，请填写正确账号及密码";
		//header("Location: login.php?msg=$msg");
		}
	} else {
		$err[] = "登录出错，该账号不存在";
	  }		
}
?>


<?php 
$page_title = "登录 - 上网自助服务";
include 'includes/head.php';
include 'includes/errors.php';
?>



<div class="container login-box">
            <h1 class="title">登录并查询上网信息</h1>
            <div class="account-wall">
                <img class="profile-img" src="assets/image/avatar.png"
                    alt="">
                <form class="form-signin" action="login.php" method="post" name="logForm">
	                <input type="text" name="usr_email" class="form-control" placeholder="学号或邮箱" required autofocus>
	                <input type="password" name="pwd" class="form-control" placeholder="密码" required>
	                <button class="btn-submit btn btn-lg btn-primary btn-block"  name="doLogin" value="Login" type="submit">
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


<?php 
include 'includes/footer.php'
 ?>
