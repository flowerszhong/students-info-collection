<?php 
/********************** MYSETTINGS.PHP**************************
This updates user settings and password
************************************************************/
include 'dbc.php';
page_protect();

$err = array();
$msg = array();

if($_POST['doUpdate'])  
{


$rs_pwd = $db->query("select pwd from students where student_id='$_SESSION[user_id]'");
$rs_pwd_row = $rs_pwd->fetch();
$old = $rs_pwd_row['pwd'];
$old_salt = substr($old,0,9);

//check for old password in md5 format
	if($old === PwdHash($_POST['pwd_old'],$old_salt))
	{
	$newsha1 = PwdHash($_POST['pwd_new']);
	$db->exec("update students set pwd='$newsha1' where student_id='$_SESSION[user_id]'");
	$msg[] = "Your new password is updated";
	//header("Location: mysettings.php?msg=Your new password is updated");
	} else
	{
	 $err[] = "Your old password is invalid";
	 //header("Location: mysettings.php?msg=Your old password is invalid");
	}

}

if($_POST['doSave'])  
{
// Filter POST data for harmful code (sanitize)
foreach($_POST as $key => $value) {
	$data[$key] = filter($value);
}


$db->exec("UPDATE users SET
			`full_name` = '$data[name]',
			`address` = '$data[address]',
			`tel` = '$data[tel]',
			`fax` = '$data[fax]',
			`country` = '$data[country]',
			`website` = '$data[web]'
			 WHERE id='$_SESSION[user_id]'
			") or die(print_r($db->errorInfo()));

//header("Location: mysettings.php?msg=Profile Sucessfully saved");
$msg[] = "Profile Sucessfully saved";
 }
 
$rs_settings = $db->query("select * from students where student_id='$_SESSION[user_id]'"); 
?>

<!DOCTYPE html>
<html>
<?php include 'assets/html/head.php';?>

<body>

<?php include 'assets/html/navbar.php';?>


<div class="container-fluid">
      <div class="row">
        <?php include 'assets/html/sidebar.php';?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

          <h2 class="sub-header">人个档案</h2>
          <div class="table-responsive">
          <?php 
        if(!empty($err))  {
           echo "<div class=\"msg\">";
          foreach ($err as $e) {
            echo "* Error - $e <br>";
            }
          echo "</div>";  
           }
           if(!empty($msg))  {
            echo "<div class=\"msg\">" . $msg[0] . "</div>";

           }
          ?>
      </p>
      <p>你可以修改部分个人信息。</p>
    <?php while ($row_settings = $rs_settings->fetch()) {?>
                <form action="mysettings.php" method="post" name="myform" id="myform">
                  <table  class="table table-striped">
                    <tr> 
                      <td>学号</td>
                      <td><input name="student_id" type="text" id="student_id" class="required" value="<? echo $row_settings['student_id']; ?>" disabled></td>
                    </tr>

                   
                    <tr> 
                      <td>姓名</td>
                      <td><input name="user_name" type="text" value="<? echo $row_settings['user_name']; ?>" disabled></td>
                    </tr>

                    <tr> 
                      <td>邮箱</td>
                      <td><input name="user_email" type="text" value="<? echo $row_settings['user_email']; ?>" disabled></td>
                    </tr>


                    <tr> 
                      <td>性别</td>
                      <td><input name="user_email" type="text" value="<? echo $row_settings['user_email']; ?>" disabled></td>
                    </tr>

                    <tr> 
                      <td>电话</td>
                      <td><input name="tel" type="text" id="tel" class="required" value="<? echo $row_settings['tel']; ?>"></td>
                    </tr>

                    <tr> 
                      <td>宿舍</td>
                      <td><input name="tel" type="text" class="required" value="<? echo $row_settings['building']; ?>"></td>
                    </tr>

                    <tr> 
                      <td>上网账号</td>
                      <td><input name="user_email" type="text" value="<? echo $row_settings['user_email']; ?>" disabled></td>
                    </tr>

                    <tr> 
                      <td>上网密码</td>
                      <td>
                        <input name="tel" type="text" id="tel" class="required" value="<? echo $row_settings['tel']; ?>">
                        <button class="btn">查看上网密码</button>
                      </td>
                    </tr>

                    <tr> 
                      <td>网费到期时间</td>
                      <td>
                        <input name="tel" type="text" class="required" value="<? echo $row_settings['building']; ?>">
                        <button class='btn'>续交网费</button>
                      </td>
                    </tr>


                    <tr> 
                      <td>年级</td>
                      <td><input name="tel" type="text" class="required" value="<? echo $row_settings['building']; ?>"></td>
                    </tr>

                    <tr> 
                      <td>系别</td>
                      <td><input name="user_email" type="text" value="<? echo $row_settings['user_email']; ?>" disabled></td>
                    </tr>

                    <tr> 
                      <td>专业</td>
                      <td>
                        <input name="tel" type="text" id="tel" class="required" value="<? echo $row_settings['tel']; ?>">
                        <button class="btn">查看上网密码</button>
                      </td>
                    </tr>

                    <tr> 
                      <td>班集</td>
                      <td>
                        <input name="tel" type="text" class="required" value="<? echo $row_settings['building']; ?>">
                        <button class='btn'>续交网费</button>
                      </td>
                    </tr>


                    <tr> 
                      <td>用户权限</td>
                      <td>
                        <input name="user_level" type="text" class="required" value="<? echo $row_settings['user_level']; ?>">
                      </td>
                    </tr>
                   
                    <tr> 
                      <td>用户权限</td>
                      <td>
                        <input name="user_level" type="text" class="required" value="<? echo $_SESSION['user_level']; ?>">
                      </td>
                    </tr>
                   
                    <tr>
                      <td colspan="2">
                        <input name="doSave" type="submit" id="doSave" value="保存">
                      </td>
                    </tr>
                  </table>
                </form>
              <?php } ?>
                <h2 class="titlehdr">修改密码</h2>
                <div class="table-responsive">
                <form name="pform" id="pform" method="post" action="">
                  <table class="table table-striped">
                    <tr> 
                      <td>旧密码</td>
                      <td><input name="pwd_old" type="password" class="required password"  id="pwd_old"></td>
                    </tr>
                    <tr> 
                      <td>输入新密码</td>
                      <td><input name="pwd_new" type="password" id="pwd_new" class="required password"  ></td>
                    </tr>

                    <tr> 
                      <td>再次输入新密码</td>
                      <td><input name="pwd_new" type="password" id="pwd_new" class="required password"  ></td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <input name="doUpdate" type="submit" id="doUpdate" value="更新">
                      </td>
                    </tr>
                  </table>
                </form>
              </div>
          </div>
        </div>
      </div>
    </div>


    <script src="assets/js/settings.js"></script>

</body>
</html>
