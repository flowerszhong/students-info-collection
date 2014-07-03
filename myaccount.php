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

$rs_available = $db->query("select count(*) as total_availiable from students where net_id<>'' and net_pwd <>'' ") or die(showDBError());
// $datenow = "2014-11-01";
// $rs_expire = $db->query("select count(*) as total_expire from students where expire_date >") or die(showDBError());
// print_r($rs_expire);


$sql_all_available = 'select count(*) from students where approved="0" and net_id<>"" and net_pwd<>""';

$sep = $sql_all_available . ' and expire_date >= "20140930"';
$oct = $sql_all_available . ' and expire_date >= "20141031"';
$nov = $sql_all_available . ' and expire_date >= "20141130"';
$dec = $sql_all_available . ' and expire_date >= "20141231"';
$sep_13 = $sep . ' and (grade=2013 or grade=13)';
$sep_14 = $sep . ' and (grade=2014 or grade=14)';
$oct_13 = $oct . ' and (grade=2013 or grade=13)';
$oct_14 = $oct . ' and (grade=2014 or grade=14)';
$nov_13 = $nov . ' and (grade=2013 or grade=13)';
$nov_14 = $nov . ' and (grade=2014 or grade=14)';
$dec_13 = $dec . ' and (grade=2013 or grade=13)';
$dec_14 = $dec . ' and (grade=2014 or grade=14)';

$sep_13_rows = $db->query($sep_13) or die(showDBError());
$sep_14_rows = $db->query($sep_14) or die(showDBError());
$oct_13_rows = $db->query($oct_13) or die(showDBError());
$oct_14_rows = $db->query($oct_14) or die(showDBError());
$nov_13_rows = $db->query($nov_13) or die(showDBError());
$nov_14_rows = $db->query($nov_14) or die(showDBError());
$dec_13_rows = $db->query($dec_13) or die(showDBError());
$dec_14_rows = $db->query($dec_14) or die(showDBError());


$sep_13_count = $sep_13_rows->fetch();
$sep_14_count = $sep_14_rows->fetch();
$oct_13_count = $oct_13_rows->fetch();
$oct_14_count = $oct_14_rows->fetch();
$nov_13_count = $nov_13_rows->fetch();
$nov_14_count = $nov_14_rows->fetch();
$dec_13_count = $dec_13_rows->fetch();
$dec_14_count = $dec_14_rows->fetch();


// list($total_pending) = mysql_fetch_row($rs_total_pending);
// list($all) = mysql_fetch_row($rs_all);
// list($active) = mysql_fetch_row($rs_active);


	$total_pending = $rs_total_pending->fetch();
	$all = $rs_all->fetch();
	$active = $rs_active->fetch();
	$available = $rs_available->fetch();
	// $expire = $rs_expire->fetch();
	
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


	<table>
		<thead>
			<tr>
				<td></td>

				<td colspan="2">9月</td>
				<td colspan="2">10月</td>
				<td colspan="2">11月</td>
				<td colspan="2">12月</td>
				<td>合计</td>

			</tr>

			<tr>
				<td>月分</td>
				<td>2013级</td>
				<td>2014级</td>
				<td>2013级</td>
				<td>2014级</td>
				<td>2013级</td>
				<td>2014级</td>
				<td>2013级</td>
				<td>2014级</td>
				<td></td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>需缴费用户数</td>
				<td><?php echo $sep_13_count[0]; ?></td>
				<td><?php echo $sep_13_count[0]; ?></td>
				<td><?php echo $oct_13_count[0]; ?></td>
				<td><?php echo $oct_14_count[0]; ?></td>
				<td><?php echo $nov_13_count[0]; ?></td>
				<td><?php echo $nov_14_count[0]; ?></td>
				<td><?php echo $dec_13_count[0]; ?></td>
				<td><?php echo $dec_14_count[0]; ?></td>
				<td></td>
			</tr>
			<tr>
				<td>可用账户总数</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<!-- <tr>
				<td>账号尚未关联用户</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td>需缴费用户数</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td>过期账号数</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>

			<tr>
				<td>用户总数</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr> -->

			<tr>
				<td>需支付电信总金额(RMB)</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		</tbody>
	</table>


	<?php }else{?>

	<div class="account-guiding">

		亲爱的同学，<br>
		如果你已经填写了相关的邮箱信息，请注意查收你的邮件，通过邮件激活你的账号。
		<br/>
		如果你未填写邮箱信息，请在<a href="mysettings.php">个人设置</a>里填写邮箱，系统会向你发送激活邮件。
		<br>

		由于我校的网络开通及收费以班集体为单位。所以请务必在<a href="mysettings.php">个人设置</a>填写正确你的系别、专业、专业方向以及班集。
		感谢你的配合。

	</div>



	



	<?php } ?>


	<?php 

		if(!checkAdmin()){

			$sql_select = "select * from students where id = $_SESSION[user_id]";
			$select_result = $db->query($sql_select) or die(showDBError());

			$row = $select_result->fetch();

			if(!$row['approved']){
				echo "<p>你的账号尚未通过验证，请填写邮箱或查收邮件，激活账号</p>";
			}

			if($row['user_email'] && $row['student_id'] && $row['grade'] && $row['department'] && $row['major']){

			}else{
				echo "<p>你的信息尚不全，请<a href='mysettings.php'>设置人个信息</a></p>";
			} 
		}
	?>




 
</div>
<script src="assets/js/settings.js"></script>
