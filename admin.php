<?php 
include 'dbc.php';
page_protect();

if(!checkAdmin()) {
header("Location: login.php");
exit();
}

$page_limit = 3; 


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

if($post['doBan'] == 'Ban') {

if(!empty($_POST['u'])) {
	foreach ($_POST['u'] as $uid) {
		$id = filter($uid);
		$db->exec("update students set banned='1' where student_id='$id' and `user_name` <> 'admin'") or die(print_r($db->errorInfo()));
	}
 }
 $ret = $_SERVER['PHP_SELF'] . '?'.$_POST['query_str'];;
 
 header("Location: $ret");
 exit();
}

if($_POST['doUnban'] == 'Unban') {

if(!empty($_POST['u'])) {
	foreach ($_POST['u'] as $uid) {
		$id = filter($uid);
		$db->exec("update students set banned='0' where student_id='$id'") or die(print_r($db->errorInfo()));
	}
 }
 $ret = $_SERVER['PHP_SELF'] . '?'.$_POST['query_str'];;
 
 header("Location: $ret");
 exit();
}

if($_POST['doDelete'] == 'Delete') {

if(!empty($_POST['u'])) {
	foreach ($_POST['u'] as $uid) {
		$id = filter($uid);
		$db->exec("delete from students where student_id='$id' and `user_name` <> 'admin'") or die(print_r($db->errorInfo()));
	}
 }
 $ret = $_SERVER['PHP_SELF'] . '?'.$_POST['query_str'];;
 
 header("Location: $ret");
 exit();
}

if($_POST['doApprove'] == 'Approve') {

if(!empty($_POST['u'])) {
	foreach ($_POST['u'] as $uid) {
		$id = filter($uid);
		$db->exec("update students set approved='1' where student_id='$id'") or die(print_r($db->errorInfo()));
		
	list($to_email) = mysql_fetch_row(mysql_query("select user_email from users where id='$uid'"));	
 
$message = 
"Hello,\n
Thank you for registering with us. Your account has been activated...\n

*****LOGIN LINK*****\n
http://$host$path/login.php

Thank You

Administrator
$host_upper
______________________________________________________
THIS IS AN AUTOMATED RESPONSE. 
***DO NOT RESPOND TO THIS EMAIL****
";

@mail($to_email, "User Activation", $message,
    "From: \"Member Registration\" <auto-reply@$host>\r\n" .
     "X-Mailer: PHP/" . phpversion()); 
	 
	}
 }
 
 $ret = $_SERVER['PHP_SELF'] . '?'.$_POST['query_str'];	 
 header("Location: $ret");
 exit();
}

$rs_all = $db->query("select count(*) as total_all from students") or die(print_r($db->errorInfo()));
$rs_active = $db->query("select count(*) as total_active from students where approved='1'") or die(print_r($db->errorInfo()));
$rs_total_pending = $db->query("select count(*) as total_pending from students where approved='0'");						   

// list($total_pending) = mysql_fetch_row($rs_total_pending);
// list($all) = mysql_fetch_row($rs_all);
// list($active) = mysql_fetch_row($rs_active);


$total_pending = $rs_total_pending->fetch();
$all = $rs_all->fetch();
$active = $rs_active->fetch();

?>
<!DOCTYPE html>
<html>
<?php 
  $page_title = "管理学生信息";
  include 'assets/html/head.php'; 
?>

<body>
<?php 
  include 'assets/html/navbar.php'; 
?>

<div class="container-fluid">
<div class="row">

<?php  
include 'assets/html/sidebar.php'; 
?>


<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

<h3 class="sub-header">统计面板</h3>
<table class="myaccount table table-striped">
  <tr>
    <td width="20%">账号总数:</td>
    <td><?php echo $all['total_all'];?></td>
  </tr>
  <tr>
    <td>已缴费账号</td>
    <td><?php echo $active['total_active']; ?></td>
  </tr>
  <tr>
    <td>过期未缴费账号</td>
    <td><?php echo $total_pending['total_pending']; ?></td>
  </tr>

  <tr>
    <td>近期将过期账号</td>
    <td><?php echo $total_pending['total_pending']; ?></td>
  </tr>
  <tr>
    <td>已验证账号</td>
    <td><?php echo $active['total_active']; ?></td>
  </tr>

  <tr>
    <td>未验证账号</td>
    <td><?php echo $active['total_active']; ?></td>
  </tr>
</table>


<h3 class="sub-header">账号搜索</h3>
<form name="form1" method="get" action="admin.php">
  <p>
    <input name="q" type="text" id="q" size="40">(可输入'学号','邮箱','上网账号')
  </p>
  <ul>
    <li>
      <input type="radio" name="qoption" value="pending" checked>所有账号</li>
    <li>
      <input type="radio" name="qoption" value="pending">待缴费用户</li>
    <li>
      <input type="radio" name="qoption" value="recent">可用账号</li>
    <li>
      <input type="radio" name="qoption" value="banned">过期账号</li>
  </ul>

  <p>
    <input name="doSearch" type="submit" id="doSearch2" value="Search"></p>
</form>

<table>
  <tr>
    <td width="74%" valign="top" style="padding: 10px;"><h2><font color="#FF0000">Administration 
        Page</font></h2>
      
      <p><?php 
	  if(!empty($msg)) {
	  echo $msg[0];
	  }
	  ?></p>
      <table width="80%" border="0" align="center" cellpadding="10" cellspacing="0" style="background-color: #E4F8FA;padding: 2px 5px;border: 1px solid #CAE4FF;" >
        <tr>
          <td></td>
        </tr>
      </table>
      <p>
  <?php if ($get['doSearch'] == 'Search') {
	  $cond = '';
	  if($get['qoption'] == 'pending') {
	  $cond = "where `approved`='0' order by date desc";
	  }
	  if($get['qoption'] == 'recent') {
	  $cond = "order by date desc";
	  }
	  if($get['qoption'] == 'banned') {
	  $cond = "where `banned`='1' order by date desc";
	  }
	  
	  if($get['q'] == '') { 
	     $sql = "select * from students $cond"; 
	  } 
	  else { 
	     $sql = "select * from students 
                where `user_email` = '$_REQUEST[q]' 
                or `user_name`='$_REQUEST[q]' 
                or `net_id`= '$_REQUEST[q]' ";
	  }

	  
	  $rs_total = $db->query($sql) or die(showDBError());
	  $total = sizeof($rs_total->fetchAll());

	  if (!isset($_GET['page']) )
		{ $start=0; } else
		{ $start = ($_GET['page'] - 1) * $page_limit; }
	  
	  $rs_results = $db->query($sql . " limit $start,$page_limit") or die(showDBError());
    $rs_results_rows = $rs_results->fetchAll();
	  $total_pages = ceil($total/$page_limit);
	  
	  ?>
      <p>Approve -&gt; A notification email will be sent to user notifying activation.<br>
        Ban -&gt; No notification email will be sent to the user. 
      <p><strong>*Note: </strong>Once the user is banned, he/she will never be 
        able to register new account with same email address. 
      <p align="right"> 
        <?php 
	  
	  // outputting the pages
		if ($total > $page_limit)
		{
		echo "<div><strong>Pages:</strong> ";
		$i = 0;
		while ($i < $page_limit)
		{
		
		
		$page_no = $i+1;
		$qstr = ereg_replace("&page=[0-9]+","",$_SERVER['QUERY_STRING']);
		echo "<a href=\"admin.php?$qstr&page=$page_no\">$page_no</a> ";
		$i++;
		}
		echo "</div>";
		}  ?>
		</p>
		<form name "searchform" action="admin.php" method="post">
        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
          <tr bgcolor="#E6F3F9"> 
            <td width="4%"><strong>ID</strong></td>
            <td> <strong>Date</strong></td>
            <td><div align="center"><strong>User Name</strong></div></td>
            <td width="24%"><strong>Email</strong></td>
            <td width="10%"><strong>Approval</strong></td>
            <td width="10%"> <strong>Banned</strong></td>
            <td width="25%">&nbsp;</td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td width="10%">&nbsp;</td>
            <td width="17%"><div align="center"></div></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>

          <?php foreach ($rs_results_rows as $rrows) {?>
           
          <tr> 
            <td><input name="u[]" type="checkbox" value="<?php echo $rrows['student_id']; ?>" id="u[]"></td>
            <td><?php echo $rrows['date']; ?></td>
            <td> <div align="center"><?php echo $rrows['user_name'];?></div></td>
            <td><?php echo $rrows['user_email']; ?></td>
            <td> <span id="approve<?php echo $rrows['id']; ?>"> 
              <?php if(!$rrows['approved']) { echo "Pending"; } else {echo "Active"; }?>
              </span> </td>
            <td><span id="ban<?php echo $rrows['id']; ?>"> 
              <?php if(!$rrows['banned']) { echo "no"; } else {echo "yes"; }?>
              </span> </td>
            <td> <font size="2"><a href="javascript:void(0);" onclick='$.get("do.php",{ cmd: "approve", id: "<?php echo $rrows['id']; ?>" } ,function(data){ $("#approve<?php echo $rrows['id']; ?>").html(data); });'>Approve</a> 
              <a href="javascript:void(0);" onclick='$.get("do.php",{ cmd: "ban", id: "<?php echo $rrows['id']; ?>" } ,function(data){ $("#ban<?php echo $rrows['id']; ?>").html(data); });'>Ban</a> 
              <a href="javascript:void(0);" onclick='$.get("do.php",{ cmd: "unban", id: "<?php echo $rrows['id']; ?>" } ,function(data){ $("#ban<?php echo $rrows['id']; ?>").html(data); });'>Unban</a> 
              <a href="javascript:void(0);" onclick='$("#edit<?php echo $rrows['id'];?>").show("slow");'>Edit</a> 
              </font> </td>
          </tr>
          <tr> 
            <td colspan="7">
			
			<div style="display:none;font: normal 11px arial; padding:10px; background: #e6f3f9" id="edit<?php echo $rrows['id']; ?>">
			
			<input type="hidden" name="id<?php echo $rrows['id']; ?>" id="id<?php echo $rrows['id']; ?>" value="<?php echo $rrows['id']; ?>">
			User Name: <input name="user_name<?php echo $rrows['id']; ?>" id="user_name<?php echo $rrows['id']; ?>" type="text" size="10" value="<?php echo $rrows['user_name']; ?>" >
			User Email:<input id="user_email<?php echo $rrows['id']; ?>" name="user_email<?php echo $rrows['id']; ?>" type="text" size="20" value="<?php echo $rrows['user_email']; ?>" >
			Level: <input id="user_level<?php echo $rrows['id']; ?>" name="user_level<?php echo $rrows['id']; ?>" type="text" size="5" value="<?php echo $rrows['user_level']; ?>" > 1->user,5->admin
			<br><br>New Password: <input id="pass<?php echo $rrows['id']; ?>" name="pass<?php echo $rrows['id']; ?>" type="text" size="20" value="" > (leave blank)
			<input name="doSave" type="button" id="doSave" value="Save" 
			onclick='$.get("do.php",{ cmd: "edit", pass:$("input#pass<?php echo $rrows['id']; ?>").val(),user_level:$("input#user_level<?php echo $rrows['id']; ?>").val(),user_email:$("input#user_email<?php echo $rrows['id']; ?>").val(),user_name: $("input#user_name<?php echo $rrows['id']; ?>").val(),id: $("input#id<?php echo $rrows['id']; ?>").val() } ,function(data){ $("#msg<?php echo $rrows['id']; ?>").html(data); });'> 
			<a  onclick='$("#edit<?php echo $rrows['id'];?>").hide();' href="javascript:void(0);">close</a>
		 
		  <div style="color:red" id="msg<?php echo $rrows['id']; ?>" name="msg<?php echo $rrows['id']; ?>"></div>
		  </div>
		  
		  </td>
          </tr>
          <?php } ?>
        </table>
	    <p><br>
          <input name="doApprove" type="submit" id="doApprove" value="Approve">
          <input name="doBan" type="submit" id="doBan" value="Ban">
          <input name="doUnban" type="submit" id="doUnban" value="Unban">
          <input name="doDelete" type="submit" id="doDelete" value="Delete">
          <input name="query_str" type="hidden" id="query_str" value="<?php echo $_SERVER['QUERY_STRING']; ?>">
          <strong>Note:</strong> If you delete the user can register again, instead 
          ban the user. </p>
        <p><strong>Edit Users:</strong> To change email, user name or password, 
          you have to delete user first and create new one with same email and 
          user name.</p>
      </form>
	  
	  <?php } ?>
      &nbsp;</p>
	  <?php
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

	  ?>
	  
      <h2><font color="#FF0000">Create New User</font></h2>
      <table width="80%" border="0" cellpadding="5" cellspacing="2" class="myaccount">
        <tr>
          <td><form name="form1" method="post" action="admin.php">
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
            <p>**All created users will be approved by default.</p></td>
        </tr>
      </table>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p></td>
    <td width="12%">&nbsp;</td>
  </tr>
</table>

</div>
</div>
</div>
</body>
</html>
