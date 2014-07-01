<?php 
/********************** MYSETTINGS.PHP**************************
This updates user settings and password
************************************************************/
include 'dbc.php';
page_protect();

$err = array();
$msg = array();


if($_POST['recharge'])  
{
	// Filter POST data for harmful code (sanitize)
	foreach($_POST as $key =>$value) {
		$data[$key] = filter($value);
	}

	if($data['charge_type']){
		$recharge_fee = 300;
	}else{
		$recharge_fee = 240;
	}


	$db->exec("UPDATE students SET
				`recharge_fee` = '$recharge_fee'
				 WHERE id='$_SESSION[user_id]'
				") or die(print_r($db->errorInfo()));

	//header("Location: mysettings.php?msg=Profile Sucessfully saved");
	$msg[] = "你续交网费申请已经提交";
	 }
 
$rs_settings = $db->query("select * from students where id='$_SESSION[user_id]'"); 
include 'includes/head.php';
include 'includes/sidebar.php';
include 'includes/errors.php';
 ?>
<div class="main">

	<h3 class="title">上网账号信息</h3>

	<?php while ($row_settings = $rs_settings->fetch()) {?>
		<table  class="table table-striped">
			<tr>
				<td>上网账号</td>
				<td>
					<?php if($row_settings['net_id']){ ?>

					<input name="student_id" type="text" id="student_id" value="<? echo $row_settings['net_id']; ?>" disabled></td>
					<?php  } else{?>
					你尚未分配上网账号
					<?php } ?>
			</tr>

			<tr>
				<td>账号密码</td>
				<td>
				<?php if($row_settings['net_id']){ ?>
					<button class="btn">查看账号密码</button>
					<label for=""><?php echo $row_settings['net_pwd']; ?></label>
				<?php } ?>
					
				</td>
			</tr>

			<!-- <tr>
				<td>缴费明细</td>
				<td>
					<label for=""><?php echo $row_settings['consumer_record']; ?></label>
				</td>
			</tr> -->

			<tr>
				<td>账号过期时间</td>
				<td>
					<label for=""><?php echo $row_settings['expire_date']; ?></label>
				</td>
			</tr>

			
		</table>
	<br>

	<?php } ?>

	<h3 class="title">续交网费</h3>
	<!-- <input type="button" class="btn" value="一年">
	<input type="button" class="btn" value="半年" > -->
	

	<form action="surf.php" method="post">

	<?php 

	$rs_settings = $db->query("select expire_date from students where id='$_SESSION[user_id]'"); 

	$row_settings1 = $rs_settings->fetch();

	 ?>

		<table>
			<tr>
				<td>
					<input type="radio" name="charge_type" id="chart_type" value='1' checked> 一年（交到学年末）
					<p>起始时间：2014-10-01</p>
					<p>到期时间：2015-09-30</p>
					<p>充值金额: 300 rmb</p>
				</td>
			</tr>
			<tr>
				<td>
					<input type="radio" name="charge_type" id="charge_type" value='0'> 半年（交到学期末）
					<p>起始时间：2014-10-01</p>
					<p>到期时间：2015-06-30</p>
					<p>充值金额: 240 rmb</p>
				</td>
			</tr>
			<tr>
				<td>
					<input type="submit" name="recharge" class="btn" value="提交续交网费申请">
				</td>
			</tr>
		</table>

	</form>

	<h3 class="title">申请修改上网账号</h3>
	


	</div>

<script src="assets/js/settings.js"></script>


<?php 
	include 'includes/footer.php';
 ?>