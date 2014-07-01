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
		$sql_update = "UPDATE students SET `department` = '$data[department]',`major` = '$data[major]',`sub_major` = '$data[sub_major]' WHERE id='$_SESSION[user_id]'";
		$db->exec($sql_update) or die(showDBError());
	}

	if($data['major']){
		$sql_update = "UPDATE students SET `major` = '$data[major]',`sub_major` = '$data[sub_major]'  WHERE id='$_SESSION[user_id]'";
		$db->exec($sql_update) or die(showDBError());
	}

	if($data['sub_major']){
		$sql_update = "UPDATE students SET `sub_major` = '$data[sub_major]' WHERE id='$_SESSION[user_id]'";
		$db->exec($sql_update) or die(showDBError());
	}


	if($data['class']){
		$sql_update = "UPDATE students SET `class` = '$data[class]' WHERE id='$_SESSION[user_id]'";
		$db->exec($sql_update) or die(showDBError());
	}

	
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
		<table  class="table table-striped" id="setting-table">

		<tr>
			<td colspan="2">账号信息
			</td>
		</tr>
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
				<td colspan="2">专业设置
				<input type="button" value="点击修改" id="change-major-setting">
				<input type="button" value="取消修改" id="cancel-setting-btn">

				</td>
			</tr>
			<tr>
				<td>年级</td>
				<td>
					<label class="lbl">
						<? echo $row_settings['grade']; ?>
					</label>

					<select name="grade" id="grade" class="editing form-control">
					    <option value="2013">2013</option>
					    <option value="2014" selected>2014</option>
					</select>
				</td>
			</tr>

			<tr>
				<td>系别</td>
				<td>
					<label class="lbl"> <?php echo $row_settings['department']; ?> </label>

					<select name="department" id="department" class="form-control editing">
					</select>
				</td>
			</tr>

			<tr>
				<td>专业</td>
				<td>

					<label class="lbl">
						<?php echo $row_settings['major'];  ?>
					</label>

					<select name="major" id="major" class="form-control editing">
					    <option value="">请选择专业</option>
					</select>
				</td>
			</tr>

			<tr>
				<td>专业方向</td>
				<td>

					<label class="lbl">
						<?php echo $row_settings['sub_major'];  ?>
					</label>

					<select name="sub_major" id="sub-major" class="form-control editing">
					    <option value="">请选择专业方向</option>
					</select>
				</td>
			</tr>

			<tr>
				<td>班级</td>
				<td>
				<label class="lbl"><?php echo $row_settings['class']; ?> 班</label>

					<select name="class" id="" class="editing"> cvalue="<?php echo $row_settings['class']; ?>" >
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
					<input name="doSave" type="submit" id="doSave" class="btn" value="保存"></td>
			</tr>
		</table>
	</form>
	<?php } ?></div>

<script src="assets/js/main.js"></script>
<script src="assets/js/settings.js"></script>
<script src="assets/js/register.js"></script>


<?php include 'includes/footer.php'; ?>



