<?php 
include 'dbc.php';
page_protect();
?>

<?php include 'includes/head.php';?>
<?php include 'includes/sidebar.php';?>


        
<div class="main">
  <h1 class="title">
  	<?php 

	$user_name = getUserName();
  	if(checkAdmin()){
  		echo '&#10023;'.$user_name ."&#10023;  管理员,你好！";
  		
  	}else{
  		echo '&#10023;'.$user_name ."&#10023;  童鞋,你好！";

  	}
  	 ?>
  </h1>

  <div class="row placeholders">
    <!-- <p>欢迎你：<?php echo $_SESSION['user_name']; ?></p> -->
  </div>


<?php
if(checkAdmin()) {

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
	<h3 class="sub-title">统计面板</h3>
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


	<?php }?>






 
</div>
<script src="assets/js/settings.js"></script>
