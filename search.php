<?php 
include 'dbc.php';
page_protect();

ini_set('max_execution_time', 1000);


if(!checkAdmin()) {
	header("Location: login.php");
	exit();
}



$host  = $_SERVER['HTTP_HOST'];
$host_upper = strtoupper($host);
$login_path = @ereg_replace('admin','',dirname($_SERVER['PHP_SELF']));
$path   = rtrim($login_path, '/\\');

// filter GET values
foreach($_GET as $key => $value) {
	$get[$key] = filter($value);
}

// filter POST values
foreach($_POST as $key => $value) {
	$post[$key] = filter($value);
}

var_dump($_GET);



$page_limit = 10; 
$record_limit = 10;

if((int)($get['record_limit'])>$record_limit){
	$record_limit = (int)($get['record_limit']);
	echo $record_limit;
}


if ($get['doSearch'] == 'Search') {
	  $cond = 'where id > 0 ';
	  if($get['qoption'] == 'pending') {
	  $cond = "where `approved`='0' order by id";
	  }
	  if($get['qoption'] == 'recent') {
	  $cond = "order by id desc";
	  }

	  if($get['grade']){
	  	$grade = (int)($get['grade']);
	  	$cond = $cond . 'and grade = '.$grade .' ';
	  }

	  if($get['major']){
	  	$cond = $cond . 'and major = "' .$get['major'].'" ';
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

	  echo $sql;

	  
	  
	  $rs_total = $db->query($sql) or die(showDBError());
	  $total = sizeof($rs_total->fetchAll());

	  if (!isset($_GET['page']) )
		{ $start=0; } 
      else
		{ $start = ($_GET['page'] - 1) * $record_limit; }
	  
	  $rs_results = $db->query($sql . " limit $start,$record_limit") or die(showDBError());
    $rs_results_rows = $rs_results->fetchAll();
    // var_dump($rs_results_rows);
    // return;
	  $total_pages = ceil($total/$record_limit);
    // echo $total_pages;
	  
	  ?>
      

          <?php foreach ($rs_results_rows as $rrows) {?>
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

                <tr>
                	<td colspan="11" class="pages">
                		

                  <?php 
          	  
              	$max_pages = min($page_limit,$total_pages);

          	  // outputting the pages
          		if ($total > $page_limit)
          		{
          		echo "<b>Pages:</b>";
          		$i = 0;
          			while ($i < $max_pages)
          			{
          			$page_no = $i+1;
          			$qstr = ereg_replace("&page=[0-9]+","",$_SERVER['QUERY_STRING']);
          			echo "<a>$page_no</a> ";
          			$i++;
          			}
          		}  ?>
                	</td>

                </tr>
<?php
}
?>
          
	 

   
