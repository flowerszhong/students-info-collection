<?php 
include 'dbc.php';
include 'register-util.php';
?>
<!DOCTYPE html>
<html>
<head>
<title>填写注册信息</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="js/jquery.validate.js"></script>

<script>
$(document).ready(function(){
    $.validator.addMethod("username", function(value, element) {
        return this.optional(element) || /^[a-z0-9\_]+$/i.test(value);
    }, "Username must contain only letters, numbers, or underscore.");

    $("#regForm").validate();
});
</script>

            <link href="styles.css" rel="stylesheet" type="text/css"></head>

<body>

<?php   
if(!empty($err))  {
    echo "<div class=\"msg\">";
    foreach ($err as $e) {
        echo "* $e <br>";
    }
    echo "</div>"; 
}
?> 
 <table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
                <tr>
                    <td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                    <td width="160" valign="top">
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                    </td>
                    <td width="732" valign="top">
        <form action="register.php" method="post" name="regForm" id="regForm" >
            <table width="95%" border="0" cellpadding="3" cellspacing="3" class="forms">
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
                    <td>netId</td>
                    <td>
                        <input type="text" name="net_id"></td>
                </tr>

                <tr>
                    <td>年级</td>
                    <td>
                        <select name="grade">
                            <option value="2012">2012</option>
                            <option value="2013">2013</option>
                            <option value="2014">2014</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>系别</td>
                    <td>
                        <select name="department">
                            <option value="op1">op1</option>
                            <option value="op2">op2</option>
                            <option value="op3">op3</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>专业</td>
                    <td>
                        <select name="major">
                            <option value="major1">major1</option>
                            <option value="major2">major2</option>
                            <option value="major3">major3</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>班集</td>
                    <td>
                        <select name="major">
                            <option value="class1">class1</option>
                            <option value="class2">class2</option>
                            <option value="class3">class3</option>
                        </select>
                    </td>
                </tr>

        </table>
        <p align="center">
            <input name="doRegister" type="submit" id="doRegister" value="Register"></p>
    </form>
</td>
<td width="196" valign="top">&nbsp;</td>
</tr>

</table>
</body>
</html>