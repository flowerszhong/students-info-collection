<?php 
include 'dbc.php';
page_protect();

if(!checkAdmin()) {
header("Location: login.php");
exit();
}


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


      <div class="toolbar">
         显示：
         <select id="row-limit">
           <option value="10">10</option>
           <option value="20">20</option>
           <option value="50">50</option>
           <option value="100">100</option>
           <option value='0'>全部</option>
         </select>页


          <input name="doApprove" type="submit" id="doApprove" value="激活">
          <input name="doDelete" type="submit" id="doDelete" value="删除">
          <input name="doAllocation" type="submit" id="doAllocation" value="分发上网账号">
      </div>
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
              <select name="grade" id="grade">
                <option name="grade" value="">
                  
                </option>
                <option name="grade" value="13">
                  13级
                </option>
                <option name="grade" value="14">
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
              <input type="button" value="搜索" id="search-btn" />
            </td>
          </tr>
        </thead>

          <tbody id="students-list">
         
          </tbody>
        </table>
	    
	  
    </div>
	 

    <script src="assets/js/settings.js"></script>
    <script src="assets/js/admin.js"></script>
    <script src="assets/js/register.js"></script>

   


<?php include 'includes/footer.php'  ?>