<?php 
include 'dbc.php';
page_protect();

if(!checkAdmin()) {
header("Location: login.php");
exit();
}

$page_limit = 10; 
$record_limit = 20;


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


if($_POST['doDelete'] == 'Delete') {

if(!empty($_POST['u'])) {
	foreach ($_POST['u'] as $uid) {
		$id = filter($uid);
		$db->exec("delete from students where student_id='$id' and `user_name` <> 'admin'") or die(showDBError());
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
?>


<?php 
  $page_title = "管理学生信息";
  include 'includes/head.php'; 
  include 'includes/sidebar.php'; 
  include 'includes/errors.php';
?>


<div class="main">

<h3 class="title">账号搜索</h3>
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

      <p>
  <?php if ($get['doSearch'] == 'Search') {
	  $cond = '';
	  if($get['qoption'] == 'pending') {
	  $cond = "where `approved`='0' order by id";
	  }
	  if($get['qoption'] == 'recent') {
	  $cond = "order by id desc";
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
		{ $start=0; } 
      else
		{ $start = ($_GET['page'] - 1) * $record_limit; }
	  
	  $rs_results = $db->query($sql . " limit $start,$record_limit") or die(showDBError());
    $rs_results_rows = $rs_results->fetchAll();
	  $total_pages = ceil($total/$record_limit);
    // echo $total_pages;
	  
	  ?>
      <p align="right"> 
        <?php 
	  
    $max_pages = min($page_limit,$total_pages);

	  // outputting the pages
		if ($total > $page_limit)
		{
		echo "<div><strong>Pages:</strong> ";
		$i = 0;
		while ($i < $max_pages)
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
        <table>
        <thead>
          <tr> 
            <td>ID</td>
            <td>学号</td>
            <td>姓名</td>
            <td>邮箱</td>
            <td>年级</td>
            <td>系</td>
            <td>专业</td>
            <td>专业方向</td>
            <td>班级</td>
            <td>是否激活</td>
            <td>操作</td>
          </tr>
          <tr> 
          <tr class='search-row'>
            <td></td>
            <td>
              <input tyep="text" class="q" name='student_id' value="" />
            </td>
            <td>
              <input tyep="text" class="q" name='student_id' value="" />
            </td>
            <td>
              <input tyep="text" class="q" name='student_id' value="" />
            </td>
            <td>
              <select name="grade">
                <option name="grade" value="">
                  
                </option>
                <option name="grade">
                  13级
                </option>
                <option name="grade">
                  14级
                </option>
              </select>
            </td>
           <td>
             <select name="department" id="department">
               <option name="department" value="">
                 
               </option>
               <option name="department">
                 13级
               </option>
               <option name="department">
                 14级
               </option>
             </select>
           </td>
            <td>
            <select id="major">
              
            </select>
            </td>
            <td>
            <select id="sub-major">
              
            </select>
            </td>
            <td>
             <select id="class-list" name="class">
               <option name="class" value=""></option>
               <option name="class" value="1班">1班</option>
               <option name="class" value="2班">2班</option>
               <option name="class" value="3班">3班</option>
               <option name="class" value="4班">4班</option>
               <option name="class" value="5班">5班</option>
               <option name="class" value="6班">6班</option>
               <option name="class" value="7班">7班</option>
               <option name="class" value="8班">8班</option>
               <option name="class" value="9班">9班</option>
               <option name="class" value="10班">10班</option>
             </select>
            </td>
            <td>
              <input tyep="text" class="q" name='student_id' value="" />
            </td>
            <td>
              <input type="button" value="搜索" />
            </td>
          </tr>
        </thead>

          <?php foreach ($rs_results_rows as $rrows) {?>
          <tbody>
          <tr> 
            <td><input name="id" type="checkbox" value="<?php echo $rrows['id']; ?>" id=""></td>
            <td><?php echo $rrows['student_id'];?></td>
            <td><?php echo $rrows['user_name'];?></td>
            <td><?php echo $rrows['user_email']; ?></td>
            <td><?php echo $rrows['grade']; ?></td>
            <td><?php echo $rrows['deparment']; ?></td>
            <td><?php echo $rrows['major']; ?></td>
            <td><?php echo $rrows['sub_major']; ?></td>
            <td><?php echo $rrows['class']; ?></td>

            <td> <span id="approve<?php echo $rrows['id']; ?>"> 
              <?php if(!$rrows['approved']) { echo '未激活'; } else {echo "已激活"; }?>
              </span> 
            </td>
            <td>
              <a href="javascript:void(0);" onclick='$.get("do.php",{ cmd: "approve", id: "<?php echo $rrows['id']; ?>" } ,function(data){ $("#approve<?php echo $rrows['id']; ?>").html(data); });'>激活</a> 
              <a class="show-edit-box">编辑</a> 
            </td>
          </tr>
          <tr class="hide"> 
            <td colspan="11">
			
			<div class="edit-box" id="edit<?php echo $rrows['id']; ?>">
			
			<input type="hidden" name="id<?php echo $rrows['id']; ?>" id="id<?php echo $rrows['id']; ?>" value="<?php echo $rrows['id']; ?>">
			User Name: 
      <input name="user_name<?php echo $rrows['id']; ?>" id="user_name<?php echo $rrows['id']; ?>" type="text" size="10" value="<?php echo $rrows['user_name']; ?>" >
			User Email:
      <input id="user_email<?php echo $rrows['id']; ?>" name="user_email<?php echo $rrows['id']; ?>" type="text" size="20" value="<?php echo $rrows['user_email']; ?>" >
			Level: 
      <input id="user_level<?php echo $rrows['id']; ?>" name="user_level<?php echo $rrows['id']; ?>" type="text" size="5" value="<?php echo $rrows['user_level']; ?>" > 1->user,5->admin
			<br><br>
      New Password: 
      <input id="pass<?php echo $rrows['id']; ?>" name="pass<?php echo $rrows['id']; ?>" type="text" size="20" value="" > (leave blank)
			<input name="doSave" type="button" id="doSave" value="Save" 
			onclick='$.get("do.php",{ cmd: "edit", 
                      pass:$("input#pass<?php echo $rrows['id']; ?>").val(),
                      user_level:$("input#user_level<?php echo $rrows['id']; ?>").val(),
                      user_email:$("input#user_email<?php echo $rrows['id']; ?>").val(),
                      user_name: $("input#user_name<?php echo $rrows['id']; ?>").val(),
                      id: $("input#id<?php echo $rrows['id']; ?>").val() 
                      } ,function(data){ $("#msg<?php echo $rrows['id']; ?>").html(data); });'> 
			<a onclick='$("#edit<?php echo $rrows['id'];?>").hide();' href="javascript:void(0);">close</a>
		 
		  <div style="color:red" id="msg<?php echo $rrows['id']; ?>" name="msg<?php echo $rrows['id']; ?>"></div>
		  </div>
		  
		  </td>
          </tr>
          <?php } ?>
          </tbody>
        </table>
	    <p>
          <input name="doApprove" type="submit" id="doApprove" value="激活">
          <input name="doAllocation" type="submit" id="doAllocation" value="分发上网账号">
          <input name="query_str" type="hidden" id="query_str" value="<?php echo $_SERVER['QUERY_STRING']; ?>">
        </p>
      </form>
	  
	  <?php } ?>
      &nbsp;</p>
    </div>
	 

    <script src="assets/js/settings.js"></script>
    <script src="assets/js/admin.js"></script>

   


<?php include 'includes/footer.php'  ?>