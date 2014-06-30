<?php 
include 'dbc.php';
$err = array();
foreach ($_GET as $key => $value) {
    $get[$key] = filter($value); //get variables are filtered.
    
}
if ($_POST['doLogin'] == 'Login' || $_POST['doLogin'] == '登录') {
    foreach ($_POST as $key => $value) {
        $data[$key] = filter($value); // post variables are filtered
        
    }
    $user_name = $data['user_name'];
    $net_id = $data['net_id'];
    if (strpos($net_id, '@') === false) {
        $net_id = $net_id . '@fsnhedu.v.gd';
    } else {
        $user_cond = "user_email='$user_email'";
    }
    $sql_select = "SELECT `id` , `net_id`,`user_name`,`approved`,`user_level` FROM students WHERE 
	           net_id='$net_id';0'";
    // echo $sql_select;
    // $result = $db->query($sql_select) or die(print_r($db->errorInfo()));
    $result = $db->query($sql_select);
    // var_dump($result);
    $row = $result->fetch();
    // var_dump($row);
    // Match row found with more than 1 results  - the user is authenticated.
    if ($row) {
        $id = $row['id'];
        $user_name = $row['user_name'];
        $user_level = $row['user_level'];
        $net_id = $row['net_id'];
        //check against salt
        if (empty($err)) {
            // this sets session and logs user in
            session_start();
            session_regenerate_id(true); //prevent against session fixation attacks.
            // this sets variables in the session
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $user_name;
            $_SESSION['user_level'] = $user_level;
            $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
            // var_dump($_SESSION);
            //update the timestamp and key for cookie
            $stamp = time();
            $ckey = GenKey();
            $db->exec("update students set `ctime`='$stamp', `ckey` = '$ckey' where id='$id'") or die($db->errorInfo());
            //set a cookie
            if (isset($_POST['remember'])) {
                echo "set a cookie";
                setcookie("user_id", $_SESSION['user_id'], time() + 60 * 60 * 24 * COOKIE_TIME_OUT, "/");
                setcookie("user_key", sha1($ckey) , time() + 60 * 60 * 24 * COOKIE_TIME_OUT, "/");
                setcookie("user_name", $_SESSION['user_name'], time() + 60 * 60 * 24 * COOKIE_TIME_OUT, "/");
            }
            header("Location: myaccount.php");
            die();
        }
    } else {
        //$msg = urlencode("Invalid Login. Please try again with correct user email and password. ");
        $err[] = "错误，请查看姓名及上网账号";
        //header("Location: login.php?msg=$msg");
        
    }
} 
?>


<?php 
$page_title = "登录 - 上网自助服务";
include 'includes/head.php';
include 'includes/errors.php';
?>



<div class="container login-box">
            <h1 class="title">13级同学请登录并激活账号</h1>
            <div class="account-wall">
                <img class="profile-img" src="assets/image/avatar.png"
                    alt="">
                <form class="form-signin" action="login-for-old-account.php" method="post" name="logForm">
	                <input type="text" name="user_name" class="form-control" placeholder="姓名，如王大锤" required autofocus>
	                <input type="text" name="net_id" class="form-control" placeholder="上网账号，如：fsVPDNhbxxxxx" required>
	                <label for="" class='field-tip'>
	                	不必输入@fsnhedu.v.gd
	                </label>
	                <button class="btn-submit btn btn-lg btn-primary btn-block"  name="doLogin" value="Login" type="submit">
	                    登录</button>
                </form>
            </div>
            <a href="register.php" class="text-center new-account">注册账号 </a>
</div>

<?php 
include 'includes/footer.php'
 ?>
