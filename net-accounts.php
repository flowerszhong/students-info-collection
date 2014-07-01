<?php 
include 'dbc.php';
page_protect();

$page_title = "数据导出";
include 'includes/head.php';
include 'includes/sidebar.php';
 ?>

<div class="main">
	<table>
	<tr>
		<td>上网账号</td>
		<td>上网密码</td>
		<td>是否关联</td>
	</tr>		
	<?php 

		$sql_select = "select * from `accounts` where used='0' limit 20";
		$rows_result = $db->query($sql_select) or die(shodDBError());
		$rows = $rows_result->fetchAll(); 
		var_dump($rows);
	?>

		<?php foreach ($rows as $rrow) {?>
			<tr>
				<td>
					<?php echo $rrow['account_id']; ?>
				</td>

				<td>
					<?php echo $rrow['account_pwd']; ?>
				</td>
				<td>
					<?php if($rrow['used']){echo "已关联";}else{
						echo "未关联";
						} ?>
				</td>
			</tr>


		<?php } ?>

	</table>



</div>

<script src="assets/js/main.js"></script>
<script src="assets/js/settings.js"></script>
<script src="assets/js/register.js"></script>

 <?php 
include 'includes/footer.php';
  ?>