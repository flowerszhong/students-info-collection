 <div class="col-sm-3 col-md-2 sidebar">
        <?php if (isset($_SESSION['user_id'])) {?>
          <ul class="nav nav-sidebar">
            <li><a href="myaccount.php">我的账号</a></li>
            <li><a href="mysettings.php">个人设置</a></li>
            <?php if($_SESSION['user_level'] === 2){?>
            <li><a href="#">代收网费</a></li>
            <?php } ?>

            <?php if($_SESSION['user_level'] == 5){?>
            <li><a href="admin.php">管理学生信息</a></li>
            <?php } ?>

            <li><a href="logout.php">退出登录</a></li>
          </ul>
        <?php } ?>
        </div>