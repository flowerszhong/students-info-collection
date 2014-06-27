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
        
        <?php include 'assets/html/sidebar.php';?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header"> 个人账号</h1>

          <div class="row placeholders">
            <!-- <p>欢迎你：<?php echo $_SESSION['user_name']; ?></p> -->
          </div>
         
        </div>
      </div>
    </div>
    <script src="assets/js/settings.js"></script>
</body>
</html>
