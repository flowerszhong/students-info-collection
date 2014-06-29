<?php 
/********************** MYSETTINGS.PHP**************************
This updates user settings and password
************************************************************/
include 'dbc.php';
page_protect();

$err = array();
$msg = array();


if($_POST['doSave'])  
{
	// Filter POST data for harmful code (sanitize)
	foreach($_POST as $key =>$value) {
		$data[$key] = filter($value);
	}

	// var_dump($_POST);

	if($data['user_name']){
		$sql_update = "UPDATE students SET `user_name` = '$data[user_name]' WHERE id='$_SESSION[user_id]'";
		$db->exec($sql_update) or die(showDBError());
	}

	if($data['student_id']){
		$sql_update = "UPDATE students SET `student_id` = '$data[student_id]' WHERE id='$_SESSION[user_id]'";
		$db->exec($sql_update) or die(showDBError());
	}


	if($data['tel']){
		$sql_update = "UPDATE students SET `tel` = '$data[tel]' WHERE id='$_SESSION[user_id]'";
		$db->exec($sql_update) or die(showDBError());
	}

	if($data['user_email']){
		$sql_update = "UPDATE students SET `user_email` = '$data[user_email]' WHERE id='$_SESSION[user_id]'";
		$db->exec($sql_update) or die(showDBError());
	}

	if($data['grade']){
		$sql_update = "UPDATE students SET `grade` = '$data[grade]' WHERE id='$_SESSION[user_id]'";
		$db->exec($sql_update) or die(showDBError());
	}

	if($data['department']){
		$sql_update = "UPDATE students SET `department` = '$data[department]' WHERE id='$_SESSION[user_id]'";
		$db->exec($sql_update) or die(showDBError());
	}

	if($data['major']){
		$sql_update = "UPDATE students SET `major` = '$data[major]' WHERE id='$_SESSION[user_id]'";
		$db->exec($sql_update) or die(showDBError());
	}


	if($data['class']){
		$sql_update = "UPDATE students SET `class` = '$data[class]' WHERE id='$_SESSION[user_id]'";
		$db->exec($sql_update) or die(showDBError());
	}

	


	// $db->exec("UPDATE students SET
	// 			`user_name` = '$data[user_name]',
	// 			`student_id` = '$data[student_id]',
	// 			`tel` = '$data[tel]',
	// 			`user_email` = '$data[user_email]',
	// 			`grade` = '$data[grade]',
	// 			`department` = '$data[department]',
	// 			`major` = '$data[major]',
	// 			`class` = '$data[class]'
	// 			 WHERE id='$_SESSION[user_id]'
	// 			") or die(print_r($db->errorInfo()));

	//header("Location: mysettings.php?msg=Profile Sucessfully saved");
	$msg[] = "人个资料更新成功";
	 }
 
$rs_settings = $db->query("select * from students where id='$_SESSION[user_id]'"); 
include 'includes/head.php';
include 'includes/sidebar.php';
include 'includes/errors.php';
 ?>
<div class="main">

	<h3 class="title">人个档案</h3>

	<p>你可以修改部分个人信息。</p>
	<?php while ($row_settings = $rs_settings->
	fetch()) {?>
	<form action="mysettings.php" method="post" name="myform" id="myform">
		<table  class="table table-striped">
			<tr>
				<td>学号</td>
				<td>
					<input name="student_id" type="text" id="student_id" class="required" value="<? echo $row_settings['student_id']; ?>" 

					<?php if(!empty($row_settings['student_id'])){
						echo "disabled";
						} ?>
					></td>
			</tr>

			<tr>
				<td>姓名</td>
				<td>
					<input name="user_name" type="text" value="<? echo $row_settings['user_name']; ?>" 
					<?php if(!empty($row_settings['user_name'])){
						echo "disabled";
						} ?>

					></td>
			</tr>

			<tr>
				<td>邮箱</td>
				<td>
					<input name="user_email" type="text" value="<? echo $row_settings['user_email']; ?>" 

					<?php if(!empty($row_settings['user_email'])){
											echo "disabled";
											} ?>

					></td>
			</tr>

			<tr>
				<td>电话</td>
				<td>
					<input name="tel" type="text" id="tel" class="required" value="<? echo $row_settings['tel']; ?>"></td>
			</tr>

			<tr>
				<td>年级</td>
				<td>
					<input name="grade" type="text" class="required" value="<? echo $row_settings['grade']; ?>">
				</td>
			</tr>

			<tr>
				<td>系别</td>
				<td>
					
					<select name="department" id="department">
					<?php if($row_settings['department']) {?>
						<option value="<?php $row_settings['department']; ?>"><?php $row_settings['department']; ?></option>
						<?php } ?>
					</select>

					</td>
			</tr>

			<tr>
				<td>专业</td>
				<td>
					<select name="major" id="major">
						<option value="<?php echo $row_settings['major']; ?>"><?php echo $row_settings['major'];  ?></option>
						option
					</select>
				</td>
			</tr>

			<tr>
				<td>班级</td>
				<td>
					<select name="grade" id="" cvalue="<?php echo $row_settings['class']; ?>" >
						<option value="1">1班</option>
						<option value="2">2班</option>
						<option value="3">3班</option>
						<option value="4">4班</option>
						<option value="5">5班</option>
						<option value="6">6班</option>
						<option value="7">7班</option>
						<option value="8">8班</option>
					</select>
				</td>
			</tr>

			<tr>
				<td colspan="2">
					<input name="doSave" type="submit" id="doSave" value="保存"></td>
			</tr>
		</table>
	</form>
	<?php } ?></div>

<script src="assets/js/settings.js"></script>
<script src="assets/js/register.js"></script>