 <div class="col-sm-3 col-md-2 sidebar">
        <?php if (isset($_SESSION['user_id'])) {?>
          <ul class="nav nav-sidebar">
            <li><a href="myaccount.php">我的账号</a></li>

            <?php if($_SESSION['user_level'] < ADMIN_LEVEL){?>
            <li><a href="mysettings.php">个人设置</a></li>
            <li><a href="surf.php">上网账号查询</a></li>
            <li><a href="change-password.php">更改密码</a></li>
            <?php } ?>
            <?php if($_SESSION['user_level'] === HEAD_LEVEL){?>
            <li><a href="#">代收网费</a></li>
            <?php } ?>

            <?php if($_SESSION['user_level'] == 5){?>
            <li><a href="admin.php">管理学生信息</a></li>
            <li><a href="net-accounts.php">上网账号表</a></li>
            <li><a href="import.php">上网账号导入</a></li>
            <li><a href="create.php">创建账号</a></li>
            <li><a href="config.php">配置系统</a></li>
            <li><a href="export.php">数据导出</a></li>
            <?php } ?>

            <li><a href="logout.php">退出登录</a></li>
          </ul>
        <?php } ?>
        </div>