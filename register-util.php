<?php 
$err = array();
                     
if($_POST['doRegister'] == 'Register') 
{ 
/******************* Filtering/Sanitizing Input *****************************
This code filters harmful script code and escapes data of all POST data
from the user submitted form.
*****************************************************************/
foreach($_POST as $key =>$value) {
    $data[$key] = filter($value);
}


// Validate User Name
if (!isUserName($data['user_name'])) {
$err[] = "错误 - 不合法的用户名.";
//header("Location: register.php?msg=$err");
//exit();
}

// Validate Email
if(!isEmail($data['usr_email'])) {
$err[] = "错误 - 不合法的邮箱地址.";
//header("Location: register.php?msg=$err");
//exit();
}
// Check User Passwords
if (!checkPwd($data['pwd'],$data['pwd2'])) {
$err[] = "错误 - 两次输入的密码不匹配";
//header("Location: register.php?msg=$err");
//exit();
}
      
$user_ip = $_SERVER['REMOTE_ADDR'];

// stores sha1 of password
$sha1pass = PwdHash($data['pwd']);

// Automatically collects the hostname or domain  like example.com) 
$host  = $_SERVER['HTTP_HOST'];
$host_upper = strtoupper($host);
$path   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

// Generates activation code simple 4 digit number
$activ_code = rand(1000,9999);

$usr_email = $data['usr_email'];
// $user_name = base64_encode($data['user_name']);
$user_name = $data['user_name'];

/************ USER EMAIL CHECK ************************************
This code does a second check on the server side if the email already exists. It 
queries the database and if it has any existing email it throws user email already exists
*******************************************************************/

$sql_select = "select count(*) as total from students where user_email='$usr_email' OR student_id='$data[student_id]'";
$rs_duplicate = $db->query($sql_select);
$row = $rs_duplicate->fetch();
$total = $row['total'];



if ($total>0)
{
$err[] = "错误 - 你已经注册过";
//header("Location: register.php?msg=$err");
//exit();
}
/***************************************************************************/
if(empty($err)) {
    $datenow = get_Datetime_Now();
    $sql_insert = "INSERT into `students`
                (`student_id`,`user_name`,`user_email`,`pwd`,`tel`,`reg_date`,`users_ip`,`activation_code`,`department`,`major`,`grade`,`class`)
                VALUES
                ('$data[student_id]','$user_name','$usr_email','$sha1pass','$data[tel]','$datenow','$user_ip','$activ_code','$data[department]','$data[major]','$data[grade]','$data[class]')
                ";

    // echo $sql_insert;
                
    $insert_result = $db->exec($sql_insert) or die("insert data failed:" . print_r($db->errorInfo()));
    $md5_id = md5($user_id);
    $update_result = $db->exec("update students set md5_id='$md5_id' where student_id='$data[student_id]'");

    header("Location: thankyou.php");  
    exit();
}
}
?>