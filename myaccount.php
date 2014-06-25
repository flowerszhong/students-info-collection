<?php 
include 'dbc.php';
page_protect();
?>

<!DOCTYPE html>
<html>
<?php include 'assets/html/head.php';?>

<body>
<?php include 'assets/html/navbar.php';?>

<div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
        <?php 
        if (isset($_SESSION['user_id'])) {?>
          <ul class="nav nav-sidebar">
            <li class="active"><a href="myaccount.php">我的账号</a></li>
            <li><a href="mysettings.php">个人设置</a></li>
            <?php if($_SESSION['user_level'] ===2){?>
            <li><a href="#">代收网费</a></li>
            <?php } ?>

            <?php if($_SESSION['user_level'] ===5){?>
            <li><a href="admin.php">设置学生权限</a></li>
            <?php } ?>

            <li><a href="logout.php">退出登录</a></li>
          </ul>
          <?php } ?>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header"> 个人账号</h1>

          <div class="row placeholders">
            <!-- <p>欢迎你：<?php echo $_SESSION['user_name']; ?></p> -->
          </div>
         
        </div>
      </div>
    </div>

</body>
</html>
