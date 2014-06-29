<?php 
include 'dbc.php';
page_protect();

$err = array();
$msg = array();

if($_POST['doUpdate'])  
{


$rs_pwd = $db->query("select pwd from students where id='$_SESSION[user_id]'");
$rs_pwd_row = $rs_pwd->fetch();
$old = $rs_pwd_row['pwd'];
$old_salt = substr($old,0,9);

//check for old password in md5 format
	if($old === PwdHash($_POST['pwd_old'],$old_salt))
	{
  	$newsha1 = PwdHash($_POST['pwd_new']);
  	$db->exec("update students set pwd='$newsha1' where id='$_SESSION[user_id]'");
  	$msg[] = "你的账号已经更新";
  	//header("Location: mysettings.php?msg=Your new password is updated");
	} else
	{
	 $err[] = "你输入的旧密码不正确";
	 //header("Location: mysettings.php?msg=Your old password is invalid");
	}

}

include 'includes/head.php';
include 'includes/sidebar.php';
?>
      <div class="main">
    
        <h3 class="sub-title">修改密码</h3>
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

   <script src="assets/js/settings.js"></script>

  <?php include 'includes/footer.php'; ?>

