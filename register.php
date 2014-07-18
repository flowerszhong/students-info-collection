<?php 
include 'dbc.php';
include 'register-util.php';
$page_title = "注册账号" ;
include 'includes/head.php';
include 'includes/errors.php';
?> 
    <form action="register.php" method="post" name="regForm" id="regForm">
        <table class="table table-striped">
            <tr>
                <td>
                    姓名
                    <span class="required"> <font color="#CC0000">*</font>
                    </span>
                </td>
                <td>
                    <input name="user_name" type="text" id="user_name" class="required"></td>
            </tr>
            <tr>
                <td>
                    电话
                    <span class="required"> <font color="#CC0000">*</font>
                    </span>
                </td>
                <td>
                    <input name="tel" type="text" id="tel" class="required"></td>
            </tr>

            <tr>
                <td colspan="2">
                    <h4> <strong>详细信息</strong>
                    </h4>
                </td>
            </tr>
            <tr>
                <td>
                    邮箱地址
                    <span class="required">
                        <font color="#CC0000">*</font>
                    </span>
                </td>
                <td>
                    <input name="usr_email" type="text" id="usr_email3" class="required email">
                    <span class="example">** Valid email please..</span>
                </td>
            </tr>
            <tr>
                <td colspan="2">上网自助服务登录密码设置</td>
            </tr>
            <tr>
                <td>
                    密码
                    <span class="required">
                        <font color="#CC0000">*</font>
                    </span>
                </td>
                <td>
                    <input name="pwd" type="password" class="required password" minlength="5" id="pwd">
                    <span class="example">** 5 chars minimum..</span>
                </td>
            </tr>
            <tr>
                <td>
                    密码确认
                    <span class="required">
                        <font color="#CC0000">*</font>
                    </span>
                </td>
                <td>
                    <input name="pwd2"  id="pwd2" class="required password" type="password" minlength="5" equalto="#pwd"></td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>

            <tr>
                <td>学号</td>
                <td>
                    <input type="text" name="student_id"></td>
            </tr>

            <tr>
                <td>年级</td>
                <td>
                    <select name="grade">
                        <option value="2013">2013</option>
                        <option value="2014" selected>2014</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>系别</td>
                <td>
                    <select name="department" id="department"  class="form-control">
                        <option value="op1">op1</option>
                        <option value="op2">op2</option>
                        <option value="op3">op3</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>专业</td>
                <td>
                    <select name="major" id="major" class="form-control">
                        <option value="">请选择专业</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>专业方向</td>
                <td>
                    <select name="sub_major" id="sub-major" class="form-control">
                        <option value="">请选择专业方向</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>班级</td>
                <td>
                    <select name="class"  class="form-control">
                        <option value="1">1班</option>
                        <option value="2">2班</option>
                        <option value="3">3班</option>
                        <option value="4">4班</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <button name="doRegister" type="submit" id="doRegister" value="Register">
                        注册
                    </button>
                </td>
            </tr>

    </table>
</form>

<script src="assets/js/register.js"></script>
<script src="assets/js/main.js"></script>

</body>
</html>



